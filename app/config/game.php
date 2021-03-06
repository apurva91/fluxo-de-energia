<?php
return array(
	'swapon'=>1,
	'boostOnSwap'=>1,//bool
	'boostFac'=>0.25,
	'facDecay' => array("god" => 0.1*(0.1*0.1/60),"investor" =>(0.01*0.01/60),"farmer" =>0.1*(0.1*0.001/60)),
	'iniLE' => array("god" => 1000000,"investor" => 500000,"farmer" => 30000),
	'stored_LE' => array("god" => 100000,"investor" => 60000,"farmer" => 10000),
	'numGods'=>6,
	'numInvs'=>11,
	'modGs'=>2,
	'modIs'=>2,
	'modFs'=>2,
	'minLE' => 1000,
	'minDecay' => '0.005',
	'leaderBoardRate'=>60,//currently in top movers
	'topmovers_update_time'=>60,
	'minRefreshRate'=>3,//seconds
	'msRefreshRate'=>2500,//miliseconds
	'notifTime'=>60,
	'notifHTMLs'=>[
	'clean'=>'<i class="btn btn-block btn-primary btn-sm fa fa-check">All Okay</i>',
	'warn_die'=>'<i class="btn btn-block btn-warning btn-sm fa fa-life-saver">	Death Warning</i>',
	'warning_down'=>'<i class="btn btn-block btn-warning btn-sm fa fa-arrow-circle-down">	Warning Down</i>',
	'stuck_wait'=>'<i class="btn btn-block btn-danger btn-sm fa fa-level-down">	Waiting for Swap Down</i>',
	'class_topper'=>'<i class="btn btn-block btn-success btn-sm fa fa-arrow-circle-up">	Level Up Candidate</i>',
	'swap_down'=>'<i class="btn btn-block btn-danger btn-sm fa fa-arrow-circle-down">	Switching Role </i>',
	'f_yeah'=>'F yeah !',
	],
	'receiptNames'=>[
	'ET'=>'Expiry Time',
	'FT'=>'Funding Time',
	'name'=>'Name',
	'category'=>'Category',
	'description'=>'Description',
	'quality'=>'Quality',
	'unit_price'=>'Unit Price',
	'avl_units'=>'Available Units',
	'total_shares'=>'Total Shares Launched',
	],
	'reloads'=>[
	'clean'=>'0',
	'warn_die'=>'0',
	'warning_down'=>'0',
	'stuck_wait'=>'0',
	'class_topper'=>'0',
	'swap_down'=>"C::get('game.swapon')",
	'f_yeah'=>'0',
	],
	'basePrices' => array("seed" => 500,"fertilizer" => 2000,"land" => 7000),

	//thresholds - better kept separate than in array now
	'facGM' => 0.09, //F yeah mode
	'facGI' => 0.005,
	'facFI' => 0.0005,//this factor may depend on number of users ?!
	'facF' => 0.00005,

	// 'commonGod'=>Common::where('category','god')->first(),
	// 'commonInvestor'=>Common::where('category','investor')->first(),
	// 'commonFarmer'=>Common::where('category','farmer')->first(),

	'godPercent'=>0.51,
	'godReturns'=>0.81, //funding returns
	'godRecovery'=>0.4, //selling returns (of ET decay)
	'farmerRecovery'=>20, //selling fruit returns

	'maxET'=>60,
	'maxFT'=>60,
	'baseCIs'=>[
	'c1'=>0.002,  //quality for product
	'c2'=>0.005, //FT
	'c3'=>0.002,  //ET
	'c4'=>0.003,  //TOL
	],

	// $fruit->sell_price = $fruit->storage_le * ($c1*$fruit->quality_factor + $c2*$fruit->ET) ; //calculate the unit_price of fruit.
	'fruitCIs'=>[
	'c1'=>0.008,  //quality for fruit
	'c2'=>0.005, //FT
	'c3'=>0.002,  //ET
	'c4'=>0.003,  //TOL
	],


	'fruitBP'=>500,  //later this wil be a factor from seed's price
	'sellC1'=>0.002,
	'sellC2'=>0.012,
	'fruitReturns' => 100, //On fetch, directly dmultiplied by the quality  (num_units=1 at a time)
	'storage_fac' => 0.5, //*goes to (storage_fac + 1)*unit_price at 100% quality


	'seedGT' =>5 * 50,//50 quality shall have 5 min	//GT=seedGT/q;more qual, less GT. quality ranges from 1 to 100.
	'maxGT'  =>999,//minutes. this is returned for unused lands in ajax req 
	'maxFertSeeds' => 3,//
	'maxQual' => 100,
	'baseQual'=>50,
	'seedQual'=>0.8, // *ly for GT ?!
	'fertQual'=>0.18, // this reduces quality range to favourable
	'landQual'=>0.18, 

	//formFields
	'createProdFields'=> ['name', 'category', 'description', 'quality', 'ET', 'FT', 'unit_price', 'avl_units', 'total_shares',],

	);
?>