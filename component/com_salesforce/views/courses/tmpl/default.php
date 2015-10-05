<?php
defined('_JEXEC') or die('Restricted access');
?>
<div ng-app="courseApp">
  
<div ng-controller="courseMainController" ng-show="showList">
  <h1 class="panel-heading">List of Courses/Seminars</h1><br/><br/>
  <form>
    <div class="form-group">
      <div class="input-group">       
        <input type="text" class="form-control course-srch-input" placeholder="Search for Course/Seminar....." ng-model="searchCourse"/>
        <div class="input-group-addon"><i class="fa fa-search"></i></div>
      </div>      
    </div>
  </form>

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="notclick">						
            				Code
          			</th>
					<th >
						<a  class="sortable-a" ng-click="sortType = 'title'; sortReverse = !sortReverse">
            				Course Title
             				<span ng-show="sortType == 'title'  && !sortReverse" class="fa fa-caret-down"></span>
            				<span ng-show="sortType == 'title'  && sortReverse" class="fa fa-caret-up"></span>
          				</a>
					</th>
					<th >
						<a  class="sortable-a" ng-click="sortType = 'startDate'; sortReverse = !sortReverse">
            				Start Date
          				<span ng-show="sortType == 'startDate'  && !sortReverse" class="fa fa-caret-down"></span>
            			<span ng-show="sortType == 'startDate'  && sortReverse" class="fa fa-caret-up"></span>
          				</a>
					</th>
					<th nowrap>
						<a  class="sortable-a" ng-click="sortType = 'finishDate'; sortReverse = !sortReverse">
            				Finish Date
          				<span ng-show="sortType == 'finishDate'  && !sortReverse" class="fa fa-caret-down"></span>
            			<span ng-show="sortType == 'finishDate'  && sortReverse" class="fa fa-caret-up"></span>
          				</a>
					</th>				
					<th class="notclick">						
            				Location
					</th>				
					<th>
						<a  class="sortable-a" ng-click="sortType = 'price'; sortReverse = !sortReverse">
            				Price*
          				<span ng-show="sortType == 'price'  && !sortReverse" class="fa fa-caret-down"></span>
            			<span ng-show="sortType == 'price'  && sortReverse" class="fa fa-caret-up"></span>
          				</a>
					</th>				
					<th ></th>				
				</tr>
			</thead>
			<tbody>				
				<tr class="sectiontableentry" ng-repeat="cor in courses | filter:search |orderBy:sortType:sortReverse">
				  <td >{{cor.code}}</td>
				  <td >{{cor.title}}</td>
				  <td nowrap>{{cor.startDate |date:'dd MMM yyyy'}}</td>
				  <td nowrap>{{cor.finishDate |date:'dd MMM yyyy'}}</td>
				  <td >{{cor.location}}</td>
			  	  <td >{{cor.price | currency:"&euro;"}}  Per Attendee
				  <i ng-show='{{cor.oapPrice != "0.00"}}' class="fa fa-info-circle" title='Price for OAP/Student {{cor.oapPrice | currency:"&euro;"}}'></i>
				  
				  </td>
				  <td nowrap>
					<a ng-show="{{cor.bookable}}" href="index.php?option=com_salesforce&view=sfbooking&courseid={{cor.Id}}" ng-click="showBooking=true;showList=false;">{{cor.book}}</a>
					<span ng-show="{{cor.showStartedCourseText}}">Not Available</span>  <i ng-show='{{ cor.showStartedCourseText}}' class="fa fa-info-circle anual-info" title='This Course/Seminar has already started. You cannot make a booking on line. Please contact the Lismullin Office.'></i>
					<span ng-show="{{cor.showAnualText}}">Not Available</span>  <i ng-show='{{cor.showAnualText}}' class="fa fa-info-circle" title='Please contact the Lismullin Office to book a place for this course.'></i>
<span ng-show="{{cor.showFullyBookedText}}">Not Available</span>  <i ng-show='{{cor.showFullyBookedText}}' class="fa fa-info-circle fully-booked-info" title='This course is now fully booked. Please contact the Lismullin Office for further information.'></i>
<span ng-show="{{cor.showStatusCancelledText}}">Not Available</span>  <i ng-show='{{cor.showStatusCancelledText}}' class="fa fa-info-circle status-cancelled-info" title='This course has been cancelled. Please contact the Lismullin Office for more information.'></i>
				</td>
			  </tr>				
			</tbody>
		</table>
</div>
</div>
