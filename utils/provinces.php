<?php

	function get_provinces() {
		$provinces = array(
			'Alberta',
			'British Columbia',
			'Manitoba',
			'New Brunswick',
			'Newfoundland and Labrador',
			'Northwest Territories',
			'Nova Scotia',
			'Nunavut',
			'Ontario',
			'Prince Edward Island',
			'Quebec',
			'Saskatchewan',
			'Yukon'
		);
		return $provinces;
	}

	// function get_capital($province) {
	// 	$capitals = [
	// 		'Alberta' => 'Calgary',
	// 		'British Columbia' => 'Vancouver',
	// 		'Manitoba' => 'Winnipeg',
	// 		'New Brunswick' => 'Moncton',
	// 		'Newfoundland and Labrador' => 'St. John\'s',
	// 		'Northwest Territories' => 'Yellowknife',
	// 		'Nova Scotia' => 'Halifax',
	// 		'Nunavut' => 'Iqaluit',
	// 		'Ontario' => 'Toronto',
	// 		'Prince Edward Island' => 'Charlottetown',
	// 		'Quebec' => 'Montreal',
	// 		'Saskatchewan' => 'Saskatoon',
	// 		'Yukon' => 'Whitehorse'
	// 	];

	// 	return $capitals[$province];
	// }

	function get_province_code($province) {
		$codes = [
			'Alberta' => 'AB',
			'British Columbia' => 'BC',
			'Manitoba' => 'MB',
			'New Brunswick' => 'NB',
			'Newfoundland and Labrador' => 'NL',
			'Northwest Territories' => 'NT',
			'Nova Scotia' => 'NS',
			'Nunavut' => 'NU',
			'Ontario' => 'ON',
			'Prince Edward Island' => 'PE',
			'Quebec' => 'QC',
			'Saskatchewan' => 'SK',
			'Yukon' => 'YT'
		];
		return $codes[$province];
	}

	function get_province($province_code) {
		$provinces = [
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NL' => 'Newfoundland and Labrador',
			'NT' => 'Northwest Territories',
			'NS' => 'Nova Scotia',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon'
		];
		return $provinces[$province_code];
	}
