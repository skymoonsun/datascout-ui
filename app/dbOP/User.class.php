<?php
require_once("crud.class.php");

class User  Extends Crud
{
    protected $table = 'user';
    protected $pk	 = 'id';
}