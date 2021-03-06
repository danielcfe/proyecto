<?php
	/**
		@author Daniel Castillo & Jhoynerk Caraballo
	*/
	class User extends MY_Model{
		public $id;
		public $ci;
		public $nombre;
		public $apellido;
		public $direccion;
		public $correo;
		public $fecha_nac;
		public $sexo;
		public $est_civil;
		public $tipo_sangre;
		public $observacion;
		public $nivel_instruccion;
		public $clave;
		public $tipo;
		public $estatus;
		public $etnia;
		public $expediente;
		public $laico;
		public $religioso;
		public $congregacion;
		public $nacionalidad;
		public $pensum_id;
		public $semestre;
		public $carrera;
		public $roles;
		

		public function __construct(){
			parent::__construct( );
		}

		public function load( $ids )
		{
			return $this->duser->getUserById( $ids );
		}

		public function loadAll( $where = '' )
		{
			return $this->duser->getAll( $where );
		}

		public function delete()
		{
			if ($this->duser->delete( $this->id )){
				$this->reset();
				return true;
			}
			return false;
		}

		public function status_Delete(){
			return $this->duser->setState();
		}

		public function loadRoles( )
		{
			$this->roles = $this->duser->getRoles( $this->id );
			if (count($this->roles)>0){
				return true;
			}
			return false;
		}

		public function getRoles(){
			return $this->roles;
		}



		public function setRoles( $roles )
		{
			$aux = array();
			$cont = 0;
			foreach ($roles as $key => $value){
				$aux[$cont]['user_id']   = $this->id;
				$aux[$cont++]['role_id'] = $value['role_id'];
			}
			$this->roles = $aux;
		}

	/*	public function save( ){
			return $this->duser->insert();
		}*/

		public function saveRoles(){
			if(count($this->roles)>0){
				return $this->duser->insertRoles( $this->roles );
			}
			return false;
		}

		public function searchUser( $ci )
		{
			$user = false;
			$user = $this->duser->findByCI( $ci );
			if ($user) {
				$user = $user[0];
				$user['requisitos'] = $this->duser->getRequisitos( $user['ci'] );
			}
			return $user;
		}		

		public function saveRequisitos( $requisitos){
			$result = false;
			$this->db->where('usuario_ci', $requisitos[0]['usuario_ci']);
			$this->db->delete('requisito_has_usuario');
			foreach ($requisitos as $requisito ){
				$result =	$this->db->insert('requisito_has_usuario', $requisito);
			}
			return $result;
		}

		public function saveCarreras( $usuario_ci, $carrera ){
			$result = false;
			$this->db->where('usuario_ci', $usuario_ci );
			$this->db->delete('estudiante_has_carrera');
			$result =	$this->db->insert('estudiante_has_carrera', $carrera);
			return $result;
		}

		public function saveRol($id_role)
		{
			$data = $this->get('all');
			$this->db->where('user_id', $this->ci );
			$this->db->delete('user_has_role');
			$rol = array('user_id' =>$this->ci, 'role_id' => $id_role, 'status' => 'ACTIVE' );
			$result = $this->db->insert( 'user_has_role', $rol );
			return $result;
		}

		public function getUserOne($ci)
		{
			$this->db->select('*');
			$this->db->where('ci', $ci);
			$query = $this->db->get('usuario');
			return $query->result_array();			
		}

	}
