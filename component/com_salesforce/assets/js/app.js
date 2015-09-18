
var app = angular.module('courseApp', []);
app.controller('courseMainController', function($scope) {
  $scope.sortType     = 'title'; // set the default sort type
  $scope.sortReverse  = false;  // set the default sort order
  $scope.searchCourse   = '';     // set the default search/filter term
  $scope.showList = true;
  $scope.showBooking = false;
  // create the list of sushi rolls
  //The sfc variable injected by php view.html.php file
  $scope.courses = sfc;
  /*$scope.sushi = [
    { code: 'Code 1', title: 'Tittle 1', startDate: 'Start Date 1', finishDate: 'Finish Date 1', price: 'PRice 1', book: 'Book 1',location: c1["code"] },
    { code: 'Code 1', title: 'Tittle 1', startDate: 'Start Date 1', finishDate: 'Finish Date 1', price: 'PRice 1', book: 'Book 1' ,location: 'location 1'},
    { code: 'Code 1', title: 'Tittle 1', startDate: 'Start Date 1', finishDate: 'Finish Date 1', price: 'PRice 1', book: 'Book 1' ,location: 'location 1'},
    { code: 'Code 1', title: 'Tittle 1', startDate: 'Start Date 1', finishDate: 'Finish Date 1', price: 'PRice 1', book: 'Book 1' ,location: 'location 1'}
  ];
  */
 $scope.search = function (item){
    var t = item.title.toLowerCase();
    var s = $scope.searchCourse.toLowerCase();
    if (t.indexOf(s)!=-1 ) {
            return true;
        }
        return false;
  };
  
});
app.controller('bookingController',function($scope){
  
  //sfcontact injected by php view.html.php file
  $scope.contact = sfcontact;
  
  });