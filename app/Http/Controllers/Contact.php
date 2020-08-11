<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Contact extends Controller
{
    //
    public function display(){
    	return view('project');
    }

    /**
     * Displays the result
     *
     **/
    public function display_result(Request $request){

    	// template inputs
    	$subjects = $request->input('subject');
    	$messages = $request->input('message');

    	// handlers
	    $subjects_array = array();
	    $messages_array = array();

    	// uploads location
    	$location = 'uploads';

    	// csv file
    	$file = $request->file('file');

    	if($file){

    		// csv filename
	    	$filename = $file->getClientOriginalName();

	    	// let's upload the file
	    	$file->move($location,$filename);
	    	
	    	// Now, let's loop, read and get the value
	    	// of the uploaded csv file.

	    	$col_vals_array = array();

	    	$filepath = public_path($location.DIRECTORY_SEPARATOR.$filename);
	    	
	    	$file = fopen($filepath,"r");

	    	// determine the number of row
	    	$idx = 0;

	    	// arrays that will handle the csv data
	    	$headers_array = array();
	    	$col_vals_array = array();

	    	// while there is something to read
	    	while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
	    		$num = count($filedata );
	    		// let's get the headers
	    		if($idx == 0){
	    			for($c=0;$c<$num;$c++){
	    				$headers_array[] = $filedata[$c];
	    			}
	                $idx++;
	                continue; 
	            }
	            // now the value of columns
	            for($c=0;$c<$num;$c++){
	            	// make the headers as key
	            	$col_vals_array[$idx][$headers_array[$c]] = $filedata[$c];
	            }
	            $idx++;

	    	}
	    	fclose($file); // close the file

	    	// now let's do the real thing
	    	foreach($col_vals_array as $item_array){	    		
	    		foreach($item_array as $csv_col=>$csv_val){
	    			// subject templates
	    			if(!empty($subjects)){	    				
		    			foreach($subjects as $subj_key=>$subj_val){		    				
		    				if(strpos($subj_val,'{{ '.$csv_col.' }}' )>-1 || strpos($subj_val,'{{'.$csv_col.'}}' )>-1){
		    					if(!empty($csv_val)){
		    						$subjects_array[] = str_replace(
		    							array('{{ '.$csv_col.' }}','{{'.$csv_col.'}}'), $csv_val, $subj_val);
		    						
		    					}
		    					if(empty($csv_val)){
		    						$sub_subjects_array = array();
		    						for($i=1;$i<count($subjects);$i++){
		    							preg_match_all('/{{(.*?)}}/', $subjects[$i], $matches);
		    							$find_key = str_replace(array('{{ ',' }}','{{','}}'),'',$matches[0]);
		    							if(isset($find_key[0])){
		    								if(array_key_exists($find_key[0], $item_array)){
			    								$sub_subjects_array[] = !empty($item_array[$find_key[0]]) ? str_replace(array('{{ '.$find_key[0].' }}','{{'.$find_key[0].'}}'), $item_array[$find_key[0]], $subjects[$i]) : "";
			    								
				    						}else{
				    							$sub_subjects_array[] = "";	
				    						}
		    							}else{
		    								$sub_subjects_array[] = "";
		    							}				
		    									    							
		    						}

		    						if(!empty($sub_subjects_array)){
		    							foreach($sub_subjects_array as $k=>$v){
			    							if(!empty($v)){
			    								$subjects_array[] = $v;
			    							}
			    						}
			    						if(!array_filter($sub_subjects_array)) {
			    							$subjects_array[] = "";
			    						}
		    						}else{
		    							$subjects_array[] = "";
		    						}
		    						
		    					}		    					
		    				}
		    				break;
		    			}// END: subject loop
		    		}// END: subject if


		    		// message templates
	    			if(!empty($messages)){	    				
		    			foreach($messages as $msg_key=>$msg_val){		    				
		    				if(strpos($msg_val,'{{ '.$csv_col.' }}' )>-1 || strpos($msg_val,'{{'.$csv_col.'}}' )>-1){
		    					if(!empty($csv_val)){
		    						$messages_array[] = str_replace(
		    							array('{{ '.$csv_col.' }}','{{'.$csv_col.'}}'), $csv_val, $msg_val);
		    						
		    					}
		    					if(empty($csv_val)){
		    						$sub_messages_array = array();
		    						for($i=1;$i<count($messages);$i++){
		    							preg_match_all('/{{(.*?)}}/', $messages[$i], $matches);
		    							$find_key = str_replace(array('{{ ',' }}','{{','}}'),'',$matches[0]);
		    							if(isset($find_key[0])){
		    								if(array_key_exists($find_key[0], $item_array)){
			    								$sub_messages_array[] = !empty($item_array[$find_key[0]]) ? str_replace(array('{{ '.$find_key[0].' }}','{{'.$find_key[0].'}}'), $item_array[$find_key[0]], $messages[$i]) : "";
			    								
				    						}else{
				    							$sub_messages_array[] = "";	
				    						}
		    							}else{
		    								$sub_messages_array[] = "";
		    							}				
		    									    							
		    						}

		    						if(!empty($sub_messages_array)){
		    							foreach($sub_messages_array as $k=>$v){
			    							if(!empty($v)){
			    								$messages_array[] = $v;
			    							}
			    						}
			    						if(!array_filter($sub_messages_array)) {
			    							$messages_array[] = "";
			    						}
		    						}else{
		    							$messages_array[] = "";
		    						}
		    						
		    					}		    					
		    				}
		    				break;
		    			}// END: message loop
		    		}// END: message if
	    		}
	    	}


    	} //: END if($file)

    	// display
    	return view('project', [
    		'subjects'	=>	$subjects_array,
    		'messages' => $messages_array
    	]);
    }

}
