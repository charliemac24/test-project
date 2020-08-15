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
	    	$key_headers_array = array();

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

	            if($idx>0){

	            	for($c=0;$c<$num;$c++){
	            		
		            	// make the headers as key
		            	$col_vals_array[$idx][$headers_array[$c]] = $filedata[$c];
		            	$key_headers_array[] = '{{'.$headers_array[$c].'}}';
		            }

		            $_search_array = array();
		            $_replace_array = array();
		            $_subj = "";
		            foreach($subjects as $subj_key=>$subj_val){

	            		// loop but just get the last 
	            		// because they're just similar
		            	$_pop = end($col_vals_array);

		            	foreach($_pop as $k=>$v){

		            		preg_match_all('/{{(.*?)}}/', $subjects[$subj_key], $matches);
		            		$find = $matches[0];

		            		if(!empty($v)) {	 
		            			if(strpos($subj_val, '{{'.$k.'}}')>-1){
		            				$_search_array[] = '{{'.$k.'}}';
				            		$_replace_array[] = $v;
				            		$_subj = $subjects[$subj_key];				            		
		            			}else{
		            				// loop the rest of the template
			            			for($x=($subj_key+1);$x<count($subjects);$x++){					            	
						            	$_pop = $col_vals_array;
						            	foreach($_pop as $k=>$v){
						            		if(!empty($v)){
						            			if(strpos($subjects[$x], '{{'.$k.'}}')>-1){
							            			$_search_array[] =  '{{'.$k.'}}';
					            					$_replace_array[] = $v;
					            					$_subj = $subjects[$x];
					            					
					            					echo $_subj."=".$k."=".$v."<br>";
					            				}
						            		}					            		
						            	}
			            			}
		            			}	            			          			
		            				            			
		            		}else{
		            			// loop the rest of the template
		            			for($x=($subj_key+1);$x<count($subjects);$x++){					            	
					            	$_pop = end($col_vals_array);
					            	foreach($_pop as $k=>$v){
					            		if(!empty($v)){
					            			if(strpos($subjects[$x], '{{'.$k.'}}')>-1){
						            			$_search_array[] =  '{{'.$k.'}}';
				            					$_replace_array[] = $v;
				            					$_subj = $subjects[$x];
				            				}
					            		}					            		
					            	}
		            			}
		            		}
		            	}
		            	
		            	$subjects_array[] = str_replace(
				            $_search_array, 
				            $_replace_array, 
				            $_subj
				        );
			            break;
		            }

		            $_search_array = array();
		            $_replace_array = array();
		            $_msg = "";
		            foreach($messages as $msg_key=>$msg_val){

	            		// loop but just get the last 
	            		// because they're just similar
		            	$_pop = end($col_vals_array);

		            	foreach($_pop as $k=>$v){

		            		preg_match_all('/{{(.*?)}}/', $messages[$msg_key], $matches);
		            		$find = $matches[0];

		            		if(!empty($v)) {	
		            			if(strpos($msg_val, '{{'.$k.'}}')>-1){
		            				$_search_array[] = '{{'.$k.'}}';
				            		$_replace_array[] = $v;
				            		$_msg = $messages[$msg_key];					            		
		            			}else{
		            				// loop the rest of the template
			            			for($x=($msg_key+1);$x<count($messages);$x++){					            	
						            	$_pop = $col_vals_array;
						            	foreach($_pop as $k=>$v){
						            		if(!empty($v)){
						            			if(strpos($messages[$x], '{{'.$k.'}}')>-1){
							            			$_search_array[] =  '{{'.$k.'}}';
					            					$_replace_array[] = $v;
					            					$_msg = $messages[$x];
					            				}
						            		}					            		
						            	}
			            			}
		            			}	            			          			
		            				            			
		            		}else{
		            			// loop the rest of the template
		            			for($x=($msg_key+1);$x<count($messages);$x++){					            	
					            	$_pop = end($col_vals_array);
					            	foreach($_pop as $k=>$v){
					            		if(!empty($v)){
					            			if(strpos($messages[$x], '{{'.$k.'}}')>-1){
						            			$_search_array[] =  '{{'.$k.'}}';
				            					$_replace_array[] = $v;
				            					$_msg = $messages[$x];
				            				}
					            		}					            		
					            	}
		            			}
		            		}
		            	}
		            	
		            	$messages_array[] = str_replace(
				            $_search_array, 
				            $_replace_array, 
				            $_msg
				        );
			            break;
		            }
		            
	            }

	            $idx++;

	    	}
	    	fclose($file); // close the file

	    }

    	// display
    	return view('project', [
    		'subjects'	=>	$subjects_array,
    		'messages' => $messages_array
    	]);
    }

}
