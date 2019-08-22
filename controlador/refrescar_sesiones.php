<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
$_SESSION['id']		=	$_SESSION['id'];
$_SESSION['nombre']	=	$_SESSION['nombre'];
$_SESSION['tipo']	=	$_SESSION['tipo'];