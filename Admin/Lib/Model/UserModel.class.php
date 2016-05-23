<?php
	/**
	 * 用户表管理模型
	 */

	class UserModel extends RelationModel{
		protected $tableName='user';

		protected $_link=array(

			'user_bank'=>array(
				'mapping_type'=>HAS_ONE,
				'foreign_key'=>'wid',
				),

			'user_address'=>array(
				'mapping_type'=>HAS_ONE,
				'foreign_key'=>'wid',
				),

			);

		/**
		 * 返回供应商信息
		 */
		public function returnSupply($where,$limit=array()){
			$field=array('wid','username','qq','supply_name','status');
			return $this->field($field)->where($where)->limit($limit)->select();
		}

		/**
		 * 返回供应商数目
		 */

		public function returnSuCount($where){
			return $this->where($where)->count();
		}
	}
?>