<?php

  echo '<select name="consumptie_type" id="consumptie_type">';
 foreach ($result as $type) {
 
      echo '<option>'.$type["consumptie_name"].'</option>';
		}
		
		echo '</select><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addConsommation">+</button>';
     