<?php
	include 'php_scripts/db_connection.php';

function showColumns($row_sale)
{
	echo '<tr>';
	echo '<td>' . $row_sale['Sale_ID'] . '</td>';
	echo '<td>' . $row_sale['Item_ID'] .'</td>';
	echo '<td>' . $row_sale['Product_Name'] .'</td>';
	echo '<td>$' . $row_sale['Price'] .'</td>';
	echo '<td>' . $row_sale['Quantity_Sold'] .'</td>';
	echo '<td>' . $row_sale['Date_Sold'] .'</td>';
	echo '<td>' . $row_sale['Discount'] . '% </td> </tr>';
	
}

function getColumns()
{

    $db_connection = connectToDb();
    $failed_row = 
    '<tr>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
    </tr>';
	

    if(!$db_connection)
	{
		echo "connection failed";
    }
    else 
	{
		echo "connection succeeded </br>";
        $sale_query = mysqli_query($db_connection, "SELECT sales.Sale_ID, sales.Item_ID, items.Product_Name, sales.Quantity_Sold, sales.Date_Sold, items.Price, sales.Discount 
        FROM sales, items 
        WHERE items.Item_ID = sales.Item_ID
        ORDER BY Date_Sold;");


        if (mysqli_num_rows($sale_query) == 0) 
        {
			echo "no results"; 
            echo $failed_row;
        }
        else
		{
			echo "retrieved results </br>";
			if((!isset($_COOKIE['View'])) || ($_COOKIE['View'] == 'Total'))
			{
				echo "Cookie = view </br>";
				while ($row_sale = mysqli_fetch_assoc($sale_query))
				{				
					showColumns($row_sale);		
				}
			}
			else
			{
				echo "no cookie </br>";
				if($_COOKIE['View'] == 'Monthly')
				{
					echo "cookie = month </br>";
					$year = date('Y');
					$month = date('m');
					
					while ($row_sale = mysqli_fetch_assoc($sale_query))
					{				
						$current = explode("-",$row_sale['Date_Sold']);
							
						if (($current[1] == $month) && ($current[0] == $year))
						{
							showColumns($row_sale);
						}			
					}
				}
				
				if($_COOKIE['View'] == 'Weekly')
				{
					echo " Cookie = Weekly </br>";

					$FirstDay = date("Y-m-d", strtotime('sunday last week'));  
					$LastDay = date("Y-m-d", strtotime('sunday this week'));  
					
					while ($row_sale = mysqli_fetch_assoc($sale_query))
					{				
						$Date = ($row_sale['Date_Sold']);
					//	if ($Date > $FirstDay && $Date < $LastDay)
					//	{
							showColumns($row_sale);
					//	}			
					}
				}
				
			}
			
			
			
        }
    }
}

?>