<?php
			$connect = mysqli_connect("localhost", "root", "", "testing");
			if(isset($_POST["query"]))
			{
				$output = 'array';
				$query = "SELECT * FROM submissions WHERE product_name LIKE '%'". mysqli_real_escape_string($connect, $_POST["query"])."%'";
				$_POST["query"]."'%'";
				$result = mysqli_query($connect, $query);
				$output = '<ul class"list-unstyled">';
				if(mysqli_num_rows($result)> 0)
				{
					while($row = mysqli_fetch_array(result))
					{
						$output .= '<li>' .$row["product_name"].'<li>';
					}
				}
				else
				{ $output .= <li>'Item Not Found'<li>'';}
					
				$output .= '</ul>'
			}
			
			
				?>