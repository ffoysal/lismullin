<?php
class Course{
    protected $title;
    protected $code;
    protected $startDate;
    protected $finishDate;
    protected $venue;
    protected $price;
    protected $description;
    protected $oapPrice;
    protected $Id;
    protected $location = null;
    protected $extID;
    protected $type;
    protected $fullyBooked;
    protected $status;
    public function __construct($t=null,$c=null,$sd=null,$fd=null,$ve=null,$pr=null,$oap=null,
    $des=null,$id=null, $ext=null,$tpe=null,$fb=null,$sta=null){
        $this->title = $t;
        $this->code = $c;
        $this->startDate = $sd;
        $this->finishDate = $fd;
        if($ve != null){
            $this->location = $ve->getAddress();    
        }
                
        $this->price = $pr;
        $this->oapPrice = $oap;
        $this->description = $des;
        $this->Id = $id;
        $this->extID = $ext;
				$this->type=$tpe;
			 	$this->fullyBooked = $fb;
				$this->status = $sta;
    }
    public function getLocation(){
        return $this->location;
    }
    
    public function getStartDate(){
        $date = new DateTime($this->startDate);
        return $date->format('d M Y');
    }
    public function getStartTime(){
        $date = new DateTime($this->startDate);
        return $date->format('H:i');  
    }
    
    public function getTitle(){
        return $this->title;
    }
    public function getFinishDate(){
        $date = new DateTime($this->finishDate);
        return $date->format('d M Y');
    }
    
    public function getFinishTime(){
        $date = new DateTime($this->finishDate);
        return $date->format('H:i');    
    }
    public function getCode(){
        return $this->code;
    }
    public function getPrice(){
        return number_format($this->price, 2, '.', '');  
    }
    
    public function getOapPrice(){
        return number_format($this->oapPrice, 2, '.', '');        
    }
    
    public function getDescription(){
        return $this->description;
    }
    public function getId(){
        return $this->Id;
    }
    public function getDates(){
        $fd = new DateTime($this->finishDate);
        $sd = new DateTime($this->startDate);
        return $sd->format('M d, Y').' - '.$fd->format('M d, Y');
    }
    
    public function getExternalID(){
        return $this->extID;
    }
	public function  isValid(){
        $today = new DateTime();
        $today->sub(new DateInterval('P2D'));
        $sd = new DateTime($this->startDate);
        if($sd < $today || $this->type === 'Annual Course' || $this->isFullyBooked() || $this->status ==='Cancelled')
            return false;
        
        return true;
        
    }
	public function showAnualText(){
		return $this->type === 'Annual Course' && $this->status !='Cancelled';
	}
	public function showStartedCourseText(){
		$today = new DateTime();
        $today->sub(new DateInterval('P2D'));
        $sd = new DateTime($this->startDate);
        if($sd < $today && $this->type !== 'Annual Course' && $this->status !='Cancelled')
            return true;
        
        return false;
	}
		
		public function getType(){
		return $this->type;
		}	

    public function isFullyBooked()
    {
        if (isset($this->fullyBooked))
            return $this->fullyBooked;
        
        return false;
    }
    public function showFullyBookedText()
    {
        if($this->showStartedCourseText() || $this->showAnualText() || $this->isValid() || $this->status ==='Cancelled')
            return false;
        return true;

    }
    public function showStatusCancelledText()
    {
        if($this->status ==='Cancelled')
            return true;
        return false;

    }
}
