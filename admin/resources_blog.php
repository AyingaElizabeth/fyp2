<?php
require('top.inc.php');
?>
<style>
  
#blog{
    padding: 15px 15px 0 15px;
  
}
#blog .blog-bx {
    position: relative;
    display: flex;
    align-items: center;
    padding-bottom: 30px;
    width: 100%;
}

#blog .bog-img {
    width: 30%;
    margin-right: 20px; 
}

#blog img {
    width: 100%; /* Change this from 30% to 100% */
    height: 300px;
    object-fit: cover; /* Add this to maintain aspect ratio */
}

#blog .blog-details {
    width: calc(70% - 20px); /* Adjust width to account for the new margin */
    height: 300px;
}
 #blog .blog-details a{
    text-decoration:none;
    font-size: 11px;
    position: relative;
    color: #000;
    font-weight: 700;
 }
 #blog .blog-details a::after{
    content: "";
    width: 50px;
    height: 1px;
    background-color: #000;
    position: absolute;
    top: 5px;
    right: -60px;
    transition: 0.35s;
 }

 #blog .blog-details a:hover{
    color: var(--hoover-color);
 }

 #blog .blog-details a:hover::after{
    background-color: var(--hoover-color);
 }

  h1{
    
    font-size: 30px;
    font-weight: 700;
    color: #00C292;
    
 }

</style>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h1 class="box-title">learn more about agriculture  </h1>

			  <div class="card-body">

				   <h4 class="box-title">Get link to various Advisory services and extensions</h4>

           <div id="blog">
    <div class="blog-bx">
        <div class="bog-img">
            <img src="images/blogres/farm.jpg" alt="image">
        </div>
        <div class="blog-details">
            <h1>find useful tips and tricks to better ur farming experice</h1>
            <p>National Agriculture Advisory Services (NAADS) is one of the statutory semi-autonomous bodies in the Ministry of Agriculture, Animal Industry and Fisheries (MAAIF); established in 2001 by an Act of Parliament (NAADS Act 2001) to specifically facilitate efficient and effective delivery of agricultural advisory services for enhanced production and productivity.</p>
            <a href="https://naads.or.ug/about-us/">FIND OUT MORE</a>
        </div>
        
    </div>
    <div class="blog-bx">
        <div class="bog-img">
            <img src="images/blogres/farm.jpg "alt="IMAGE">
        </div>
        <div class="blog-details">
            <h1>Get to know about market prices with Infotrade</h1>
            <p>The Infotrade market information portal is designed as a one step area for “Information for Trade” it give trade users the opportunity to explore daily, weekly, month and quarterly prices and weather information reports.</p>
            <a href="">FIND OUT MORE </a>
        </div>
      
    </div>

    <div class="blog-bx">
        <div class="bog-img">
            <img src="images/blogres/farm.jpg" alt="">
        </div>
        <div class="blog-details">
            <h1>Get to know about market prices with Infotrade</h1>
            <p>The Infotrade market information portal is designed as a one step area for “Information for Trade” it give trade users the opportunity to explore daily, weekly, month and quarterly prices and weather information reports.</p>
            <a href="">FIND OUT MORE </a>
        </div>
      
    </div>
  
     
</div>
           
          
        </div>
				</div>
			</div>
		  </div>
	   </div>
	</div>
</div>



<?php
require('footer.inc.php');
?>