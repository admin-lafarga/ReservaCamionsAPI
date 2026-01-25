import mysql.connector
import bcrypt
from datetime import datetime, timedelta

# ===============================
# CONFIGURACIÓN DE CONEXIONES
# ===============================

OLD_DB = {
    'host': 'citaprevia.lafarga.es',
    'user': 'larfarga_externo',
    'password': 'cLP2xy*G9VZKZzj#1DGu',
    'database': 'larfarga_recepciomercaderies',
}

NEW_DB = {
    'host': '10.10.201.4',
    'port': 3306,
    'user': 'secofficer',
    'password': 'laFarga$2k17',
    'database': 'tms',
}

# ===============================
# MAPPINGS
# ===============================

DIA_MAP = {
    'lunes': 1,
    'martes': 2,
    'miércoles': 3,
    'miercoles': 3,
    'jueves': 4,
    'viernes': 5,
    'sábado': 6,
    'sabado': 6,
    'domingo': 7
}

IDIOMA_MAP = {
    91: 'es',
    92: 'cat',
    93: 'en',
    94: 'fr'
}

# ===============================
# FUNCIONES AUXILIARES
# ===============================

def conectar(db_conf):
    return mysql.connector.connect(**db_conf)

def insert_many(cursor, table, columns, rows):
    if not rows:
        return []
    placeholders = ', '.join(['%s'] * len(columns))
    cols = ', '.join(columns)
    sql = f"INSERT INTO {table} ({cols}) VALUES ({placeholders})"
    cursor.executemany(sql, rows)
    if cursor.lastrowid:
        start_id = cursor.lastrowid
        return list(range(start_id, start_id + len(rows)))
    return []

def hash_password(plain):
    return hashlib.sha256(str(plain).encode('utf-8')).hexdigest()

# ===============================
# MIGRACIONES
# ===============================

def migrar_empresas(old_cur, new_cur):
    print("→ Migrando empresas...")
    old_cur.execute("SELECT ID_Empresa, Nom_Empresa, Descripcio, created_at FROM rm_empresa")
    rows = old_cur.fetchall()
    empresas_data = [(nombre or '', descr or '', created_at, None) for _, nombre, descr, created_at in rows]
    new_ids = insert_many(new_cur, "empresas_lfycs", ["nombre", "descripcion", "created_at", "updated_at"], empresas_data)
    empresa_map = {}
    if new_ids and len(new_ids) == len(rows):
        for row, new_id in zip(rows, new_ids):
            empresa_map[row[0]] = new_id
    else:
        new_cur.execute("SELECT empresa_lfycs_id, nombre FROM empresas_lfycs")
        existing = {r[1]: r[0] for r in new_cur.fetchall()}
        for _, nombre, _, _ in rows:
            empresa_map_nombre = existing.get(nombre)
            if empresa_map_nombre:
                pass
    print(f"   {len(empresas_data)} empresas migradas.")
    return empresa_map

def migrar_estados(old_cur, new_cur):
    print("→ Migrando estados...")
    old_cur.execute("SELECT ID_Status, Nom_Status, Descripcio, created_at FROM rm_status")
    rows = old_cur.fetchall()
    estados_data = [(nombre or '', descr or '', created_at, None) for _, nombre, descr, created_at in rows]
    insert_many(new_cur, "estados", ["nombre", "descripcion", "created_at", "updated_at"], estados_data)
    print(f"   {len(estados_data)} estados migrados.")

def migrar_muelles(old_cur, new_cur, empresa_map):
    print("→ Migrando muelles...")
    old_cur.execute("SELECT ID_Moll, Nom_Moll, Descripcio, Color, created_at, ID_Empresa FROM rm_moll")
    rows = old_cur.fetchall()
    muelles_data = []
    muelle_old_ids = []
    for id_moll, nom, descr, color, created_at, id_empresa in rows:
        empresa_nueva_id = empresa_map.get(id_empresa)
        if not empresa_nueva_id:
            print(f"⚠️ Empresa ID {id_empresa} no migrada. Muelle '{nom}' omitido.")
            continue
        muelles_data.append((empresa_nueva_id, nom or '', descr or '', color or '', created_at, None))
        muelle_old_ids.append(id_moll)
    new_ids = insert_many(new_cur, "muelles", ["empresa_lfycs_id", "nombre", "descripcion", "color", "created_at", "updated_at"], muelles_data)
    muelle_map = dict(zip(muelle_old_ids, new_ids)) if new_ids else {}
    print(f"   {len(muelles_data)} muelles migrados.")
    return muelle_map

def migrar_horarios(old_cur, new_cur, muelle_map):
    print("→ Migrando horarios de muelles...")
    old_cur.execute("SELECT id, dia, tipo, inici, fin, id_moll, created_at FROM rm_horaris_nou")
    rows = old_cur.fetchall()
    horarios_data = []
    for _, dia, tipo, inicio, fin, id_moll, created_at in rows:
        dia_num = DIA_MAP.get(dia.lower()) if isinstance(dia, str) else None
        if not dia_num:
            print(f"⚠️ Día desconocido '{dia}'. Se omite fila.")
            continue
        muelle_nuevo = muelle_map.get(id_moll)
        if not muelle_nuevo:
            print(f"⚠️ Muelle ID {id_moll} no migrado. Se omite horario.")
            continue
        horarios_data.append((muelle_nuevo, dia_num, inicio, fin, created_at, None))
    insert_many(new_cur, "horarios_muelles", ["muelle_id", "dia_semana", "inicio", "fin", "created_at", "updated_at"], horarios_data)
    print(f"   {len(horarios_data)} horarios migrados.")

def migrar_materiales(old_cur, new_cur):
    print("→ Migrando materiales...")
    old_cur.execute("SELECT ID_Material, CodiSAP, Nom_Material, created_at FROM rm_material")
    rows = old_cur.fetchall()
    material_map = {}

    for id_old, codsap, nombre, created_at in rows:
        codsap_nuevo = codsap or ''
        suffix = 1

        while True:
            new_cur.execute("SELECT material_id FROM materiales WHERE codigo_sap=%s", (codsap_nuevo,))
            if new_cur.fetchone() is None:
                break
            codsap_nuevo = f"{codsap}_{suffix}"
            suffix += 1

        new_cur.execute(
            "INSERT INTO materiales (codigo_sap, nombre, created_at, updated_at) VALUES (%s,%s,%s,%s)",
            (codsap_nuevo, nombre or '', created_at, None)
        )
        new_id = new_cur.lastrowid
        material_map[id_old] = new_id

    print(f"   {len(rows)} materiales procesados.")
    return material_map


def migrar_material_muelles(old_cur, new_cur, material_map, muelle_map):
    print("→ Migrando relaciones material_muelles...")

    # Obtener todos los materiales con sus muelles
    old_cur.execute("SELECT ID_Material, MollsPermesos FROM rm_material")
    rows = old_cur.fetchall()

    count = 0
    seen = set()  # Para evitar duplicados reales

    for id_material_old, muelles_str in rows:
        material_id = material_map.get(id_material_old)
        if not material_id or not muelles_str:
            continue
        # Limpiar y convertir los mollspermesos a lista de ints
        muelles_ids = []
        for m in muelles_str.split(';'):
            m_clean = m.strip()
            if m_clean.isdigit():
                muelles_ids.append(int(m_clean))
            elif m_clean != '':
                print(f"⚠️ Ignorado valor no numérico en MollsPermesos: '{m_clean}' para material {id_material_old}")

        for muelle_old in muelles_ids:
            muelle_id = muelle_map.get(muelle_old)
            if muelle_id:
                key = (material_id, muelle_id)
                if key in seen:
                    print(f"⚠️ RELACIÓN YA EXISTE: material_id={material_id}, muelle_id={muelle_id}")
                    continue

                try:
                    new_cur.execute(
                        "INSERT INTO material_muelles (material_id, muelle_id, created_at, updated_at) VALUES (%s,%s,%s,%s)",
                        (material_id, muelle_id, None, None)
                    )
                    count += 1
                    seen.add(key)
                except Exception as e:
                    print(f"❌ Error insertando relación material_id={material_id}, muelle_id={muelle_id}: {e}")
                    raise

    print(f"   {count} relaciones material-muelle insertadas (conflictos mostrados).")


def migrar_material_tipo_camiones(old_cur, new_cur, material_map, tipo_camion_map):
    """
    Migra relaciones material <-> tipo_camiones.
    Soporta rangos en CamionsPermesos como '2:5'.
    """
    print("→ Migrando relaciones material_tipo_camiones...")

    old_cur.execute("SELECT ID_Material, CamionsPermesos FROM rm_material")
    rows = old_cur.fetchall()
    seen = set()
    count = 0

    for id_material_old, camiones_str in rows:
        material_id = material_map.get(id_material_old)
        if not material_id or not camiones_str:
            continue

        camiones_ids = []
        for part in camiones_str.split(';'):
            part = part.strip()
            if not part:
                continue

            # Expandir rangos tipo "2:5"
            if ':' in part:
                try:
                    start, end = map(int, part.split(':'))
                    camiones_ids.extend(range(start, end + 1))
                except ValueError:
                    print(f"⚠️ Valor de rango inválido '{part}' para material {id_material_old}")
            elif part.isdigit():
                camiones_ids.append(int(part))
            else:
                print(f"⚠️ Ignorado valor no numérico '{part}' para material {id_material_old}")

        for tipo_camion_old in camiones_ids:
            tipo_camion_id = tipo_camion_map.get(tipo_camion_old)
            if tipo_camion_id:
                key = (material_id, tipo_camion_id)
                if key in seen:
                    continue
                new_cur.execute("""
                    INSERT INTO material_tipo_camiones
                    (material_id, tipo_camion_id, created_at, updated_at)
                    VALUES (%s,%s,%s,%s)
                """, (material_id, tipo_camion_id, None, None))
                seen.add(key)
                count += 1

    print(f"   {count} relaciones material-tipo_camion insertadas.")




def migrar_tipo_camiones(old_cur, new_cur):
    print("→ Migrando tipo_camiones...")
    old_cur.execute("SELECT ID_TipusCamio, Nom_TipusCamio, Descripcio, tDescargaA, bloquea_muelles, created_at FROM rm_tipuscamio")
    rows = old_cur.fetchall()
    tipo_camion_map = {}
    for id_old, nombre, descripcion, tA, bloquea_muelles, created_at in rows:
        new_cur.execute("""
        INSERT INTO tipo_camiones (nombre, descripcion, tiempo_descarga_1, bloqueo_muelles, created_at, updated_at)
        VALUES (%s,%s,%s,%s,%s,%s)
        """, (nombre or '', descripcion or '', float(tA) if tA else 0, int(bloquea_muelles) if bloquea_muelles else 0, created_at, None))
        new_id = new_cur.lastrowid
        tipo_camion_map[id_old] = new_id
    print(f"   {len(rows)} tipos de camión insertados.")
    return tipo_camion_map


def migrar_tipo_proveedores(old_cur, new_cur):
    """
    Migra los tipos de proveedor de la base antigua a la nueva.
    Devuelve un diccionario {old_tipo_proveedor_id: new_tipo_proveedor_id}.
    """
    print("→ Migrando tipos de proveedor...")

    old_cur.execute("SELECT ID_tipus_proveidor, Nom_tipus_proveidor, created_at FROM rm_tipus_proveidor")
    rows = old_cur.fetchall()
    tipo_proveedor_map = {}

    for old_id, nombre, created_at in rows:
        new_cur.execute(
            "INSERT INTO tipo_proveedores (nombre, created_at, updated_at) VALUES (%s, %s, %s)",
            (nombre or '', created_at, None)
        )
        new_id = new_cur.lastrowid
        tipo_proveedor_map[old_id] = new_id

    print(f"   {len(rows)} tipos de proveedor migrados.")
    return tipo_proveedor_map



def migrar_entidades(old_cur, new_cur, tipo_proveedor_map):
    """
    Migra entidades respetando herencia:
    - Proveedores -> entidades + proveedores
    - Transportistas -> entidades + transportistas
    Devuelve dos mapas: {old_id: entidad_id}
    """
    print("→ Migrando entidades...")

    proveedor_map = {}
    transportista_map = {}

    # ---------- PROVEEDORES ----------
    old_cur.execute("""
        SELECT 
            ID_Proveidor,
            Nom_Proveidor,
            Abreujat,
            NIF,
            PIN,
            Nom_Contacte,
            email,
            Tel1,
            Tel2,
            Alerta,
            Codigo_SAP,
            ID_tipus_proveidor,  -- <- aquí
            ID_Traduccio_Lenguas,
            created_at
        FROM rm_proveidor
    """)
    proveedores = old_cur.fetchall()
    print(f"   → {len(proveedores)} proveedores encontrados")

    for (
        old_id, nombre, abrev, nif, pin, contacto,
        email, tel1, tel2, alerta, codigo_sap,
        old_tipo_id, idioma_id, created_at
    ) in proveedores:

        tipo_proveedor_id = tipo_proveedor_map.get(old_tipo_id, 1)  # fallback a 1 si no existe

        if not tipo_proveedor_id:
            print(f"⚠️ Tipo de proveedor {old_tipo_id} no encontrado, omitiendo proveedor {nombre}")
            continue
        
        idioma_final = IDIOMA_MAP.get(idioma_id, "es")

        # Insertar la ENTIDAD base
        new_cur.execute("""
            INSERT INTO entidades
            (nombre, abreviatura, nif, pin, nombre_contacto, email, telefono1, telefono2,
             alerta, idioma, created_at, updated_at)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
        """, (
            nombre or '',
            abrev or '',
            nif or '',
            pin or '',
            contacto or '',
            email or '',
            tel1 or '',
            tel2 or '',
            int(alerta) if alerta is not None else 1,
            idioma_final,
            created_at,
            None
        ))

        entidad_id = new_cur.lastrowid

        # Insertar en PROVEEDORES
        # Nota: email_notificaciones se puede mapear desde email, o vacío si quieres
        tipo_proveedor_id = tipo_proveedor_map.get(old_tipo_id, 1)  # fallback a 1 si no existe
        new_cur.execute("""
            INSERT INTO proveedores
            (tipo_proveedor_id, entidad_id, email_notificaciones, codigo_sap, created_at, updated_at)
            VALUES (%s,%s,%s,%s,%s,%s)
        """, (
            tipo_proveedor_id,  # puedes ajustar el tipo_proveedor_id según corresponda
            entidad_id,
            email or '',
            codigo_sap,  # según definición de la tabla, aquí va un timestamp? si es string, ajusta
            created_at,
            None
        ))

        proveedor_map[old_id] = new_cur.lastrowid

    # ---------- TRANSPORTISTAS ----------
    old_cur.execute("""
        SELECT
            ID_Transport,
            Nom_Transport,
            Abreujat,
            NIF,
            PIN,
            Nom_Contacte,
            email,
            Tel1,
            Tel2,
            ID_Traduccio_Lenguas,
            created_at,
            puede_gestionar
        FROM rm_transport
    """)
    transportistas = old_cur.fetchall()
    print(f"   → {len(transportistas)} transportistas encontrados")

    for (
        old_id, nombre, abrev, nif, pin, contacto,
        email, tel1, tel2, idioma_id, created_at, puede_gestionar
    ) in transportistas:

        idioma_final = IDIOMA_MAP.get(idioma_id, "es")

        # Insertar ENTIDAD base
        new_cur.execute("""
            INSERT INTO entidades
            (nombre, abreviatura, nif, pin, nombre_contacto, email, telefono1, telefono2,
             alerta, idioma, created_at, updated_at)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
        """, (
            nombre or '',
            abrev or '',
            nif or '',
            pin or '',
            contacto or '',
            email or '',
            tel1 or '',
            tel2 or '',
            1,  # alerta default
            idioma_final,
            created_at,
            None
        ))

        entidad_id = new_cur.lastrowid

        # Insertar en TRANSPORTISTAS
        new_cur.execute("""
            INSERT INTO transportistas
            (entidad_id, puede_gestionar, created_at, updated_at)
            VALUES (%s,%s,%s,%s)
        """, (
            entidad_id,
            int(puede_gestionar) if puede_gestionar is not None else 0,
            created_at,
            None
        ))

        transportista_map[old_id] = entidad_id

    print("   Entidades migradas correctamente.")
    return proveedor_map, transportista_map


def migrar_roles(old_cur, new_cur):
    """
    Migra los roles desde rm_tipususuari hacia la tabla roles.
    Devuelve un diccionario de mapeo {old_id: new_id}
    """
    print("→ Migrando roles...")

    old_cur.execute("""
        SELECT ID_TipusUsuari, Nom_TipusUsuari, Descripcio, created_at
        FROM larfarga_recepciomercaderies.rm_tipususuari
        WHERE Estat = 1 OR Estat IS NULL
    """)

    roles = old_cur.fetchall()
    print(f"   → {len(roles)} roles encontrados en sistema antiguo")

    rol_map = {}

    for old_id, nombre, descripcion, created_at in roles:
        new_cur.execute("""
            INSERT INTO roles (nombre, descripcion, created_at, updated_at)
            VALUES (%s, %s, %s, %s)
        """, (nombre, descripcion, created_at, created_at))

        new_id = new_cur.lastrowid
        rol_map[old_id] = new_id

    print(f"   ✓ {len(rol_map)} roles migrados correctamente.")
    return rol_map


def migrar_usuarios(old_cur, new_cur, rol_map):
    """
    Migra usuarios desde rm_usuari hacia la tabla users.
    Necesita rol_map: {old_rol_id: new_rol_id}
    """
    print("→ Migrando usuarios...")

    old_cur.execute("""
        SELECT ID_Usuari, Nom_Usuari, Nom, Cognom, ID_TipusUsuari, PIN,
               NIF, email, Tel1, Password, ID_Traduccio_Lenguas, Estat,
               created_at, createdBy, Tel2
        FROM larfarga_recepciomercaderies.rm_usuari
    """)

    usuarios = old_cur.fetchall()
    print(f"   → {len(usuarios)} usuarios encontrados")

    count = 0

    for row in usuarios:
        (
            ID_Usuari, Nom_Usuari, Nom, Cognom, ID_TipusUsuari, PIN,
            NIF, email, Tel1, Password, ID_Traduccio_Lenguas, Estat,
            created_at, createdBy, Tel2
        ) = row

        # Mapear rol
        rol_id = rol_map.get(ID_TipusUsuari, 3)  # fallback: 3 = usuario básico

        # Mapear idioma simplificado
        idioma = {
            1: "cat",
            2: "es",
            3: "en",
            4: "fr",
        }.get(ID_Traduccio_Lenguas, "es")

        try:
            temp_password = bcrypt.hashpw(b"changeme123!", bcrypt.gensalt()).decode()

            new_cur.execute("""
                INSERT INTO users
                (rol_id, nombre, apellidos, email_verified_at, username, contraseña, contraseña_antigua, email,
                 remember_token, created_at, updated_at, NIF, tel1, idioma)
                VALUES (%s, %s, %s, NULL, %s, %s, %s, %s, NULL, %s, %s, %s, %s, %s)
            """, (
                rol_id,
                Nom,
                Cognom,
                Nom_Usuari,
                temp_password,
                Password,
                email,
                created_at,
                created_at,
                NIF,
                Tel1,
                idioma
            ))

            count += 1

        except Exception as e:
            print(f"   ⚠️ Error insertando usuario {ID_Usuari}: {e}")

    print(f"   ✓ {count} usuarios migrados.")


# ===============================
# SCRIPT PRINCIPAL
# ===============================

def main():
    print("Iniciando migración...\n")
    
    old_conn = conectar(OLD_DB)
    new_conn = conectar(NEW_DB)
    old_cur = old_conn.cursor()
    new_cur = new_conn.cursor()

    try:
        # -----------------------
        # EMPRESAS, ESTADOS Y MUELLES
        # -----------------------
        empresa_map = migrar_empresas(old_cur, new_cur)
        migrar_estados(old_cur, new_cur)
        muelle_map = migrar_muelles(old_cur, new_cur, empresa_map)
        migrar_horarios(old_cur, new_cur, muelle_map)

        # -----------------------
        # TIPO CAMIONES
        # -----------------------
        migrar_tipo_camiones(old_cur, new_cur)
        new_cur.execute("SELECT tipo_camion_id, nombre FROM tipo_camiones")
        tipo_camion_map = {row[0]: row[0] for row in new_cur.fetchall()}

        # -----------------------
        # MATERIALES
        # -----------------------
        material_map = migrar_materiales(old_cur, new_cur)

        # -----------------------
        # RELACIONES MATERIALES
        # -----------------------
        migrar_material_muelles(old_cur, new_cur, material_map, muelle_map)
        migrar_material_tipo_camiones(old_cur, new_cur, material_map, tipo_camion_map)

        # -----------------------
        # TIPOS DE PROVEEDORES
        # -----------------------
        tipo_proveedor_map = migrar_tipo_proveedores(old_cur, new_cur)

        # -----------------------
        # ENTIDADES (PROVEEDORES / TRANSPORTISTAS)
        # -----------------------
        proveedor_map, transportista_map = migrar_entidades(old_cur, new_cur, tipo_proveedor_map)

        # -----------------------
        # ROLES
        # -----------------------
        rol_map = migrar_roles(old_cur, new_cur)

        # -----------------------
        # USUARIOS
        # -----------------------
        migrar_usuarios(old_cur, new_cur, rol_map)
        
        # -----------------------
        # RESERVAS (PRÓXIMOS 7 DÍAS)
        # -----------------------
        # migrar_reservas(old_cur, new_cur, empresa_map, proveedor_map, transportista_map, muelle_map, material_map, tipo_camion_map)

        new_conn.commit()
        print("\n✅ Migración completada con éxito.")

    except Exception as e:
        new_conn.rollback()
        print("\n❌ Error durante la migración:", e)
    finally:
        old_cur.close()
        new_cur.close()
        old_conn.close()
        new_conn.close()



if __name__ == "__main__":
    main()
