<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model {

	public static function render(){
		$User = \Request::session()->get('user');
		$isSuperuser = \App\User::isSuperuser();
		$account_role = $User->user_role;
		
		$Side =[];
		
		//Groups
		$Groups = \DB::select("SELECT * FROM mgt_app_groups ORDER BY group_order");
		if(isset($Groups[0])){
			foreach($Groups as $key_group => $Group){
				if($isSuperuser){
					$sQ ="SELECT 
							APP.app_id,
							APP.app_name,
							APP.app_slug,
							APP.app_desc,
							APP.app_icon,
							APP.app_parent,
							APP.app_group
						FROM mgt_app APP 
						WHERE APP.app_group='".$Group->group_id."' 
							AND APP.app_parent=0 
							AND APP.app_active='1'
						ORDER BY APP.app_order";
				}else{
					$sCond ="";
					$sQ ="SELECT 
							APP.app_id,
							APP.app_name,
							APP.app_slug,
							APP.app_desc,
							APP.app_icon,
							APP.app_parent,
							APP.app_group
						FROM mgt_app APP
							JOIN mgt_role_to_app R2A ON APP.app_id=R2A.app_id 
							JOIN mgt_role_to_permissions R2P ON R2A.role_id=R2P.role_id 
							JOIN mgt_permissions PERM ON R2P.perm_id=PERM.perm_id 
						WHERE R2A.role_id = '".$account_role."' AND APP.app_active='1' AND APP.app_group='".$Group->group_id."'
							AND PERM.perm_name=CONCAT('view-', APP.app_slug)
							AND APP.app_parent=0 ".$sCond."
						ORDER BY APP.app_order";
				}
					
				$App =  \DB::select($sQ);
				
				if(isset($App[0])){
					$Side[$key_group]['group'] = (array)$Group;
					foreach($App as $key=>$app){						
						$Side[$key_group]['group']['app'][$key] = (array)$app;						
						if($isSuperuser){
							$sQ ="SELECT 
									APP.app_id,
									APP.app_name,
									APP.app_slug,
									APP.app_desc,
									APP.app_icon,
									APP.app_parent,
									APP.app_group
								FROM mgt_app APP 
								WHERE APP.app_parent='".$app->app_id."' 
									AND APP.app_active='1'
								ORDER BY APP.app_order";
						}else{
							$sQ ="SELECT 
								APP.app_id,
								APP.app_name,
								APP.app_slug,
								APP.app_desc,
								APP.app_icon,
								APP.app_parent,
								APP.app_group
							FROM mgt_app APP
								JOIN mgt_role_to_app R2A ON APP.app_id=R2A.app_id 
								JOIN mgt_role_to_permissions R2P ON R2A.role_id=R2P.role_id 
								JOIN mgt_permissions PERM ON R2P.perm_id=PERM.perm_id 
							WHERE R2A.role_id = '".$account_role."' AND APP.app_active='1' 
								AND PERM.perm_name=CONCAT('view-', APP.app_slug)
								AND APP.app_parent='".$app->app_id."' 
								AND APP.app_active='1'
							ORDER BY APP.app_order";
						}
						$Mod = \DB::select($sQ);
						if(isset($Mod[0])){
							$Side[$key_group]['group']['app'][$key]['children'] = $Mod;
						}
					}
				}
			}
		}
		
		//dd($Side);
		
		if($Side){
			Sidebar::renderAsHTML($Side);
		}
	}
	
	
	/*
	* Render As HTML
	*/
	private static function renderAsHTML($dataGroups){
		$sHTML ='';
		$count =0;
		foreach($dataGroups as $key=>$arDataGroup){
			$objGroup = (object)$arDataGroup['group'];
			if($objGroup->group_id == 1){ //Ungroup
				
			}else{
				if($count > 0){
					$sHTML .='<li class="sidenav-divider mb-1"></li>';
				}
				$sHTML .='<li class="sidenav-header small font-weight-semibold">'.$objGroup->group_name.'</li>';
			}
			$count++;
			
			if(isset($objGroup->app)){
				$segment_1 = \Request::segment(1);
				foreach($objGroup->app as $arApp){
					$objApp = (object) $arApp;
					
					$sHTML .='<li class="sidenav-item '.$objApp->app_slug.'">';
					
					if(!isset($objApp->children)){ //don't have child menus
						$routeName = str_replace("-", "_", $objApp->app_slug);
						$hasRoute = \Route::has($routeName);
						$sHTML .='<a '.($hasRoute ? '' : ' title="'.$routeName.'"').' href="'.($hasRoute ? route($routeName) : '').'" class="sidenav-link"><i class="sidenav-icon '.$objApp->app_icon.'"></i>
								  <div>'.$objApp->app_name.'</div>
								</a>';
					}else{
						$sHTML .='<a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon '.$objApp->app_icon.'"></i>
								  <div>'.$objApp->app_name.'</div>
								</a>';
					}
					if(isset($objApp->children)){
						$sHTML .='<ul class="sidenav-menu">';
						foreach($objApp->children as $arModule){
							$objModule = (object) $arModule;
							$routeName = str_replace("-", "_", $objModule->app_slug);
							$hasChildRoute = \Route::has($routeName);
							$sHTML .='<li class="sidenav-item '.$objModule->app_slug.'">
									<a '.($hasChildRoute ?'':'title="'.$routeName.'"').' href="'.($hasChildRoute ? route($routeName) : '#'.$routeName).'" class="sidenav-link">
									  <div>'.$objModule->app_name.'</div>
									</a>
								</li>';
							
						}
						$sHTML .='</ul>';
					}
					
					$sHTML .='</li>';
				}
			}
		}
		
		echo $sHTML;
	}
	
	
}