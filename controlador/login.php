<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
require_once "../modelo/Gestion_usuarios.php";
$obj_estion_usuarios =new Gestion_usuarios();

if(isset($_POST['op']))
{
	switch ($_POST['op'])
	{
		case 'login':
			$usuario = trim($_POST['login']);
			$clave   = trim($_POST['clave']);

			$usuario = limpiar_cadena($usuario);
			$clave   = limpiar_cadena($clave);
			$clave   = hash("SHA256",$clave);

			$result  = $obj_estion_usuarios->login($usuario,$clave);

			if( $result['cuenta'] > 0)
			{
				$_SESSION['id']		=	$result['id'];
		        $_SESSION['nombre']	=	$result['nombre_completo'];
		        $_SESSION['tipo']	=	$result['tipo'];
		        if( $_SESSION['tipo']=='Administrador' )
		        {	
		        	echo "inicio_admin";
		        }
		        else if( $_SESSION['tipo']=='Técnico')
		        {
		        	echo "inicio_tecnico";
		        }
		        else if( $_SESSION['tipo']=='Secretario')
		        {
		        	echo "inicio_secretario";
		        }
			}
			else
			{
				echo 0;
			}

		break;
	}

}
