<!DOCTYPE html>
<html>
    <head>        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <style type="text/css">
            .add-template,
            .remove-template{
                text-align: right;
            }
            .add-template:hover,
            .remove-template:hover{
                cursor: pointer;
            }
            .remove-template{
                margin-top:5px;
            }
            .remove-template a{
                padding:5px;
            }
        </style>
    </head>
    <body>        
        <div class="container">

            <h2>Laravel Test</h2>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                </thead>
                @if(!empty($subjects))
                @foreach ($subjects as $k=>$v)
                    <tr>
                        <td>{{ $subjects[$k] ?? "" }}</td>
                        <td>{{ $messages[$k] ?? "" }}</td>
                    </tr>
                @endforeach
                @endif
            </table>

        	<form method="post" action="{{ route('result') }}" enctype="multipart/form-data">
                (Upload csv)
        		<input type="file" name="file" class="form-control"/><br>
        		<input type="text" name="subject[]" placeholder="Subject" class="form-control"/><br>
        		<textarea name="message[]" placeholder="Message" class="form-control" rows="10"></textarea><br><br>
                <div class="additional-templates"></div>
                <div class="add-template"><a class="btn btn-success">+ Add Template</a></div>
        		<input type="submit" value="Submit" class="btn btn-primary"/>   
        		<input type="hidden" name="_token" value="{{ csrf_token() }}">   	
        	</form>
        </div>
        <footer>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery(document).on('click','.add-template a',function(){
                        jQuery('.additional-templates').append('<div class="template-item"><input type="text" name="subject[]" placeholder="Subject" class="form-control"/><br>'+ 
                '<textarea name="message[]" placeholder="Message" class="form-control" rows="10"></textarea><div class="remove-template"><a class="btn btn-danger">- Remove Template</a></div><br><br></div>');
                    });

                    jQuery(document).on('click','.remove-template a',function(){
                        jQuery(this).parent().parent().fadeOut(function(){
                            jQuery(this).remove();
                        });
                    });
                });
            </script>
        </footer>
    </body>
</html>	


