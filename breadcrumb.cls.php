<?php
/*
	Class breadcrumb
	Author: Aryashree Pritikrishna
	Email Id: aryashree@etechbuddy.com
	15/01/2010 - 17:25 PM	
	
	Description: This class can generate bread crumb like navigation bars
	It outputs an HTML ordered list of navigation links. 
	
	$page_title => Internal use in function display()
	$separator => character betwen breadcrumbs
	$exclude => array containing exclude pages
	$cant => Cant of breadcrumbs to show
	$home_page => home page name
	$style => class name to use in display()
	
	Example:
	include("breadcrumb.class.php");
	$bc = new breadcrumb();
	$bc->exclude = array("event_register");
	$bc->style = "class_name";
	$bc->display();
	
*/

	class breadcrumb
	{
		var $page_title = "";
		var $separator = " &raquo; ";
		var $exclude = array();
		var $cant = 5;
		var $home_page = "home";
		var $style = "";
		 
		#constructor
		function breadcrumb($separator = " &raquo; "){
			$this->separator = $separator;
		}
		
		#get page_title
		function SEM_GetBC_Name($from){
			$afrom = explode("/", $from);
			$pos = (sizeof($afrom))-1;
			$title = $afrom[$pos];
			$atitle = explode(".", $title);
			if($atitle[0]=='index')
			{
				$atitle[0] = 'home';
			}
			return $atitle[0];
		}
		
		function checkCommingFrom(){
			if($this->page_title == $this->home_page){
				$_SESSION["breadcrumb"] = "";
			}
		}

		function display()
		{
			$refer = $_SERVER['REQUEST_URI'];
			$this->page_title = $this->SEM_GetBC_Name($refer);

			//Clear breadcumbs is coming from $home_page
			$this->checkCommingFrom();

			if(!in_array($this->page_title, $this->exclude))
			{
				$this->page_title = str_replace("-"," ",$this->page_title);
				$this->page_title = ucwords($this->page_title);
				if( (isset($_SESSION["breadcrumb"])) && ($_SESSION["breadcrumb"] != "") )
				{
					$aPageTitle = explode(' ', $this->page_title);
					$cntPageTitle=count($aPageTitle);
					
					if( ($this->page_title != "") && (stristr($_SESSION["breadcrumb"], $aPageTitle[0])) && ($cntPageTitle>1))
					{ 
						if((stristr($_SESSION["breadcrumb"], $this->page_title)==false))
						{
							$_SESSION["breadcrumb"] .= $this->separator."<a href='".$_SERVER['REQUEST_URI']."'>".$this->page_title."</a>";
						}
					}
					else
					{
						$_SESSION["breadcrumb"] = "<a href='index.php'>Home</a>".$this->separator."<a href='".$_SERVER['REQUEST_URI']."'>".$this->page_title."</a>";
					}
				}
				else
				{
					$_SESSION["breadcrumb"] = "<a href='index.php'>Home</a>";
				}	
			}
			
			//Create breadcrumbs
			$aItems = split($this->separator, $_SESSION["breadcrumb"]);
			if(sizeof($aItems) > 0){
				//Check sttyle
				if($this->style == ""){
					echo "<style>
						.breadcrumb, .breadcrumb a:link, .breadcrumb a:visited{
							padding-left:3px;
							color:black;
						}
					</style>";	
					echo "<div class='breadcrumb'>";
				}else{
					echo "<div class='".$this->style."'>";
				}	
				for($i=0; $i<sizeof($aItems); $i++){
					echo $aItems[$i].$this->separator;
				}
				echo "</div>";
			}	
		}
	}
?>
