
<style>
#chart2{
max-width: 80%;
margin: 0 10%;
min-height: 400px;
}
.carousel-control {
  max-width: 10%;
}
</style>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
          <div class="item active">
            <div id="chart2"></div>
          </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Load c3.css -->
<link href="lib/c3-0.4.10/c3.css" rel="stylesheet">

<!-- Load d3.js and c3.js -->
<script src="lib/d3/d3.min.js" charset="utf-8"></script>
<script src="lib/c3-0.4.10/c3.min.js"></script>

<script src="lib/plotly/plotly-latest.min.js"></script>

<script type="text/javascript" src="js/splitChart.js"></script>
