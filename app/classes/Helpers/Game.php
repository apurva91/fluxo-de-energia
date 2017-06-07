<?php
namespace Helpers;
use \App\Models;
use C,User,Common,Log;

class Game
{

	public static function sysLE(){
		return Game::thresholdsFor('god')['sysLE'];
	}

	public static function thresholdsFor($cat){
		$t=C::get('game.minRefreshRate'); 
		$time=time();
		$common = Common::where('category',$cat)->first();

		if($time - $common->prev_time < $t){
			$sysLE = $common->sysLE;
			$upperTHR = $common->upperTHR;
			$lowerTHR = $common->lowerTHR;
		}
		else{
			//update after refreshrate
		    $f0= C::get('game.facGM'); //F yeah mode
		    $f1= C::get('game.facGI');
		    $f2= C::get('game.facFI');
		    $f3= C::get('game.facF');
		    if($cat=='god'){$fac1=$f0;$fac2=$f1;}
		    if($cat=='investor'){$fac1=$f1;$fac2=$f2;}
		    if($cat=='farmer'){$fac1=$f2;$fac2=$f3;}
		    $sysLE= User::all()->sum('le');
		    $upperTHR=$sysLE*$fac1;
		    $lowerTHR=$sysLE*$fac2;
	    //uodate in table
		    $common->sysLE=$sysLE;
		    $common->upperTHR=$upperTHR;
		    $common->lowerTHR=$lowerTHR;
		    $common->prev_time=$time; $common->save();
		}
		return ['sysLE'=>$sysLE,'upperTHR'=>$upperTHR, 'lowerTHR'=>$lowerTHR, 'time'=>$time];
	}
}