
    <footer class="footer">
	    <div class="container">
	        <nav class="pull-left">
	            <ul>
					<li>
						<a href="http://www.creative-tim.com">
							Creative Tim
						</a>
					</li>
					<li>
						<a href="http://presentation.creative-tim.com">
						   About Us
						</a>
					</li>
					<li>
						<a href="http://blog.creative-tim.com">
						   Blog
						</a>
					</li>
					<li>
						<a href="http://www.creative-tim.com/license">
							Licenses
						</a>
					</li>
	            </ul>
	        </nav>
	        <div class="copyright pull-right">
	            &copy; 2016, made with <i class="material-icons">favorite</i> by Creative Tim for a better web.
	        </div>
	    </div>
	</footer>
</div>
</body>

<!--   Core JS Files   -->
<script src="/js/challenge/jquery.min.js" type="text/javascript"></script>
<script src="/js/challenge/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/challenge/material.min.js"></script>

<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="/js/challenge/nouislider.min.js" type="text/javascript"></script>

<!--  Plugin for the Datepicker, full documentation here: http://www.eyecon.ro/bootstrap-datepicker/ -->
<script src="/js/challenge/bootstrap-datepicker.js" type="text/javascript"></script>

<!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
<script src="/js/challenge/material-kit.js" type="text/javascript"></script>

<script type="text/javascript">
	$().ready(function(){
		// the body of this function is in material-kit.js
		materialKit.initSliders();
           window_width = $(window).width();
            if (window_width >= 992){
               big_image = $('.wrapper > .header');
				$(window).on('scroll', materialKitDemo.checkScrollForParallax);
		}
	});
</script>
</html>
