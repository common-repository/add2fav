<?php
/**
 	Add2Fav

	Add URL to user's favorites

 * @author Christian Salazar H. <christiansalazarh@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php
*/
class Add2Fav {
	public $label_add;
	public $label_rem;

	public $key_name_add = 'add2fav_label_add';
	public $key_name_rem = 'add2fav_label_rem';
	public $key_name_reg = 'add2fav_label_reg';
	public $key_name_off = 'add2fav_label_off';
	public $def_add = "Add to Favorites";
	public $def_rem = "Remove from Favorites";
	public $def_reg = "";
	public $def_off = "#";

	private $url;
	private $lastaction;

	public function getOptionAdd(){
		return $this->getOption($this->key_name_add,$this->def_add);	
	}

	public function getOptionRem(){
		return $this->getOption($this->key_name_rem,$this->def_rem);	
	}

	public function getOptionReg(){
   		return $this->getOption($this->key_name_reg,$this->def_reg);	
   	}

	public function getOptionOff(){
   		return $this->getOption($this->key_name_off,$this->def_off);	
	}

	public function saveOptionAdd($v){
		$vv = trim($v);
		if(empty($vv)) $vv = $this->def_add;
		update_option($this->key_name_add, $vv);
	}

	public function saveOptionRem($v){
   		$vv = trim($v);
   		if(empty($vv)) $vv = $this->def_rem;
   		update_option($this->key_name_rem, $vv);
   	}

	public function saveOptionReg($v){
   		$vv = trim($v);
   		if(empty($vv)) $vv = $this->def_reg;
   		update_option($this->key_name_reg, $vv);
   	}

	public function saveOptionOff($v){
   		$vv = trim($v);
   		if(empty($vv)) $vv = $this->def_off;
   		update_option($this->key_name_off, $vv);
   	}

	/*
	 	called to inform the new instance about the current URL
		to be processed for the current logged on user
	 */
	public function prepare($url){
		$this->url = trim(rtrim($url,"#/?")," ");
	}

	public function exportJs(){
		return json_encode(array(
			'label'=>
				$this->getCurrentUserLabel(),
			'status'=>
				$this->getCurrentUserStatus(),
			'url'=>
				$this->getOptionOff(),
			'hasuser'=>
				$this->isUserLoggedOn(),
			'lastaction'=>
				$this->lastaction,
			'lasturl'=>
				$this->url,
			)
		);
	}

	/*
	 	take an action, 
	 */
	public function toggle(){
		if($this->isUserLoggedOn()){
			if($this->isDataSaved($this->url)){
				$this->removeData($this->url);
				$this->lastaction = 'removed';
			}else{
				$this->saveData($this->url);
				$this->lastaction = 'added';
			}
		}
	}

	/*
	 	query if this URL is saved for the current user
	 	
	 */
	public function getCurrentUserStatus(){
		return "mustreg";
	}

	/*
	 	returns the appropiated label depending on
		the association between the URL in the current user
	 */
	public function getCurrentUserLabel(){
		if($this->isUserLoggedOn()){
			if($this->isDataSaved($this->url)){
				return $this->getOptionRem();
			}else
				return $this->getOptionAdd();
		}
		else
		return $this->getOptionReg();
	}

	/*
	 	create the layout
	 */
	public function buildTag($iconname, $cssname){
		if($this->isUserLoggedOn() || 
			((false==$this->isUserLoggedOn()) && ($this->getOptionReg()!=''))){
			$iconurl = $this->getIcon($iconname);                       	
			return "<div class='".$cssname." add2favlink'>"        	
				."<img src='".$iconurl."' /><label>".$this->loadingImg()	
				."</label></div>";
		}
		else
		return "";
	}

	public function buildTagList($iconname, $cssname, $height=''){
		if($this->isUserLoggedOn()){
			$iconurl = $this->getIcon($iconname);
			$opts = "";
			$img = "<img class='list-image' src='".$iconurl."'/>";
			foreach($this->listFavorites() as $data)
				$opts .= "<li style='margin: 0; padding: 0;'><a href='".$data."'>"
					.$img.$data."</a></li>";
			$s = "";
			if($height != ''){
			$height = trim(rtrim($height,"px"))."px";
			if($height != "")
				$s = " style='height: $height; overflow-y: scroll;' ";
			}
			$c = "";
			if($cssname != '')
				$c = " ".$cssname;
			return 
			"
			<!-- add2fav widget begins -->
			
			<div class='add2favlist$c' $s >
				<ul style='list-style: none; margin: 0; padding: 0;'>$opts</ul>
			</div>
			<!-- add2fav widget ends -->
			";
		}
		else
		return "<!-- add2favlist user is logged off -->";
	}

	private function getIcon($iconname,$ext='png'){
		$pdir = rtrim(plugins_url( '', __FILE__ ),'/').'/';
		return $pdir.$iconname.".".$ext;
	}

	private function loadingImg(){
		return "<img src='".$this->getIcon('loading','gif')."' />";
	}

	private function getOption($key, $def){
		$v = trim(get_option($key));	
		if(empty($v))
			return $def;
		return $v;
	}

	private function isUserLoggedOn(){
		return is_user_logged_in();
	}

	private function isDataSaved($data){
		foreach(get_user_meta(get_current_user_id(),'add2fav_data') as $_data)
			if($data==$_data)
				return true;
		return false;
	}

	public function listFavorites(){
		return get_user_meta(get_current_user_id(),'add2fav_data');	
	}


	private function saveData($data){
		add_user_meta(get_current_user_id(),'add2fav_data',$data,false);
	}

	private function removeData($data){
		delete_user_meta(get_current_user_id(), 'add2fav_data', $data);	
	}
}
