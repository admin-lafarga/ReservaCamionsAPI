<?php
$u = App\Models\Entidad::find(1);
echo "TOKEN_GENERADO:" . $u->createToken('test')->plainTextToken;
