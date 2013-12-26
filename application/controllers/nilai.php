<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nilai extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    
    $this->load->helper(array('url','form','date','oembed'));
    $this->load->library('session');
    
  }
  
  // Unused for now.
	public function index()
	{
	   
	}
	
	public function home($when='') {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->session->set_flashdata('lasturl', current_url());
	 
	 $when = $this->uri->segment(2);
	 if (!$when) { $when = ''; }
	 
	 $this->load->database();
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
   $today = mktime(0, 0, 0, date('n'), date('j'));
	 
	 if ($when == '') {
	   $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users ON users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
	   
	   $data['when'] = 'all';
	   
	 } elseif ($when == 'today') {
    	 
	   $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(marks.dateadded) > ".$today." AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
	   
	   $data['when'] = $when;
	   
   } elseif ($when == 'yesterday') {
   
    $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(marks.dateadded) > ".$yesterday." AND UNIX_TIMESTAMP(marks.dateadded) < ".$today." AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
    
    $data['when'] = $when;
    
	 } elseif ($when == 'archive') {
	   $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.status = 'archive' ORDER BY users_marks.id DESC LIMIT 100");
	   
	   $data['when'] = 'archive';
	 
	 } else {
	 
	 }

	 if ($marks->num_rows() > 0) {
	   $data['marks'] = $marks->result_array();
    } else {
      $data['marks'] = false;
    }
    
    
   $groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
   if ($groups->num_rows() > 0) {
    $data['groups']['belong'] = $groups->result_array();
   } 
   
   $invites = $this->db->query("SELECT groups_invites.*, groups_invites.id as inviteid, groups.*, users.emailaddress as invitedemail, users.id as invitedbyid FROM groups_invites LEFT JOIN groups ON groups_invites.groupid=groups.id LEFT JOIN users ON groups_invites.invitedby=users.id WHERE groups_invites.emailaddress = '".$this->session->userdata('emailaddress')."' AND groups_invites.status = ''");
   
   if ($invites->num_rows() > 0) $data['invites'] = $invites->result_array();
    
    
    if ($this->session->userdata('emailaddress') == 'colin@cdevroe.com') { 
      $usercount = $this->db->query("SELECT COUNT(*) as numusers FROM users WHERE status = 'paid'");
      $markcount = $this->db->query("SELECT COUNT(*) as nummarks FROM marks");
      $groupcount = $this->db->query("SELECT COUNT(*) as numgroups FROM groups");
      $groupmembers = $this->db->query("SELECT COUNT(*) as numgroupmembers from users_groups");
      
      $usercount = $usercount->result_array();
      $markcount = $markcount->result_array();
      $groupcount = $groupcount->result_array();
      $groupmembers = $groupmembers->result_array();
      
      $data['usercount'] = $usercount[0]['numusers'];
      $data['markcount'] = $markcount[0]['nummarks'];
      $data['groupcount'] = $groupcount[0]['numgroups'];
      $data['groupmemberscount'] = $groupmembers[0]['numgroupmembers'];
    }
    
    $data['label'] = '';
    $data['group']['groupuid'] = '';
    
    $this->load->view('marks',$data);

	}
	
	public function bylabel() {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->session->set_flashdata('lasturl', current_url());
	 
	 $label = $this->uri->segment(3);
	 if ($label == 'readlater') { $label = 'Read Later'; } // Hack for readlater URLs.
	 if ($label == 'unlabeled') { $label = ''; }
	 
	 $this->load->database();
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
   $today = mktime(0, 0, 0, date('n'), date('j'));
	 
	 $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.tags = '".$label."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
	 
	 if ($label == '') { $label = 'unlabeled'; } // switch it back
	   
	 if ($marks->num_rows() > 0) {
	   $data['marks'] = $marks->result_array();
    } else {
      $data['marks'] = false;
    }
    
    $groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
   if ($groups->num_rows() > 0) {
    $data['groups']['belong'] = $groups->result_array();
   } 
    
    $data['label'] = $label;
    $data['group']['groupuid'] = '';
    $data['when'] = 'all';
    
    $this->load->view('marks',$data);

	}
	
	public function bygroup() {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->session->set_flashdata('lasturl', current_url());
	 
	 $groupuid = $this->uri->segment(2);
	 
	 $this->load->database();
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
   $today = mktime(0, 0, 0, date('n'), date('j'));
   
   // General group information
   $group = $this->db->query("SELECT * FROM groups WHERE uid = '".$groupuid."'");
   if ($group->num_rows() > 0) {
    
     $group = $group->result_array();
     
     $data['group']['name'] = $group[0]['name'];
	   $data['group']['description'] = $group[0]['description'];
	   $data['group']['groupuid'] = $groupuid;
	   $data['group']['owner'] = $group[0]['createdby'];
    
    $groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = '".$group[0]['id']."'");
	  $data['group']['member_count'] = $groupmembers->num_rows();
   
   } else {
    show_404();
   }
   
   
	 $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded, groups.id as groupid FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users ON users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND groups.uid='".$groupuid."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
	   
	 if ($marks->num_rows() > 0) {
	   $data['marks'] = $marks->result_array();
    } else {
      $data['marks'] = false;
    }
    
    
    
    $groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
   if ($groups->num_rows() > 0) {
    $data['groups']['belong'] = $groups->result_array();
   } 
    
    $data['label'] = '';
    $data['when'] = 'all';
    
    $this->load->view('marks',$data);

	}

	public function search() {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->session->set_flashdata('lasturl', current_url());

	 $s = $this->input->post('s');
	 
	 $this->load->database();
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
	 $today = mktime(0, 0, 0, date('n'), date('j'));
	 
	 $marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND marks.title LIKE '%".$s."%' ORDER BY users_marks.id DESC LIMIT 100");

	 //print_r($marks); exit;
	   
	 if ($marks->num_rows() > 0) {
	   $data['marks'] = $marks->result_array();
    } else {
      $data['marks'] = false;
    }
    
    $groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
   if ($groups->num_rows() > 0) {
    $data['groups']['belong'] = $groups->result_array();
   } 
    
    $data['search'] = $s;
    $data['label'] = '';
    $data['group']['groupuid'] = '';
    $data['when'] = 'all';
    
    $this->load->view('marks',$data);

	}

	public function add() {
	 
	 $this->session->set_flashdata('addurl', current_url());
	 
	 if (!$this->session->userdata('userid') && $this->session->userdata('status') != 'paid') { redirect(''); }
	 $this->load->database();
	 
	 $title = $this->input->get('title', TRUE);
	 $url = $this->input->get('url', TRUE);	 
	 
	 if ($url == 'chrome://newtab/') { exit('Whoops! You can not mark this page. But, good on ya for using Chrome.'); }
	 
	 // Parse URL to determine domain
	 $parsedUrl = parse_url($url);


	 
	 // Data checks
   if ($title == '') { $title = $parsedUrl['host']; }
	 
	 $this->db->insert('marks',array('title'=>$title,'url'=>$url));
	 $urlid = $this->db->insert_id();
	 
	 $this->db->insert('users_marks',array('urlid'=>$urlid,'userid'=>$this->session->userdata('userid'),'addedby'=>$this->session->userdata('userid')));
   
   // First, check for user smart labels
   $smartlabel = $this->db->query("SELECT * FROM users_smartlabels WHERE domain = '".strtolower($parsedUrl['host'])."' AND userid = '".$this->session->userdata('userid')."'");
   
   if ($smartlabel->num_rows() > 0) {  // smart label found
    $label = $smartlabel->row();
    $this->addlabel($urlid,$label->label);
    $data['labeladded'] = TRUE;
    $data['userlabeladded'] = TRUE;
   }
   
   // Second, if no user smart labels were found,
   // go through the defaults. B'okay?
   if (!$this->session->flashdata('labeladded')) { 
	 
	 // Figure out if it matches any default labels
	 // Label accordingly.
	 $smartlabel = $this->checkdefaultsmartlabel($parsedUrl);
	 if ($smartlabel[0] == TRUE) {
	   $this->addlabel($urlid,$smartlabel[1]);
	   $data['labeladded'] = TRUE;
	 }
	 } // end if no user smart label
	 
	 $data['markadded'] = TRUE;
	 
	 $mark = $this->db->query("SELECT * FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.urlid='".$urlid."'");
   	
   if ($mark->num_rows() > 0) {
    $mark = $mark->result_array();
    
    $data['title'] = $mark[0]['title'];
    $data['url'] = $mark[0]['url'];
    $data['urlid'] = $mark[0]['urlid']; 
    $data['tags'] = $mark[0]['tags'];
    $data['group'] = $mark[0]['groups'];
    $data['note'] = $mark[0]['note'];
    $data['addedby'] = $mark[0]['addedby'];
    
    $data['urldomain'] = strtolower($parsedUrl['host']);
    
     $groups = $this->db->query("SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid=".$this->session->userdata('userid'));
   if ($groups->num_rows() > 0) {
    $data['groups']['belong'] = $groups->result_array();
   } 

   // This is a patch I did on December 23 2013. Cuz $data['group']['belong'] was erring locally but not on live. Please fix asap.
    
    $data['label'] = '';
    $data['when'] = '';
    if ( $data['group'] == '') {
    	$data['group'] = array();
    	$data['group']['belong'] = '';
    	$data['group']['groupuid'] = '';
    }
    
    $data['groupid']= '';

    //print_r($data); exit;
    
    $this->load->view('editpop',$data);
   } else {
     show_404();
   }

	}
	
	public function addlabel($urlid='',$label='') {
	 
	 if (!$this->session->userdata('userid')) { redirect('home'); }
	 $this->load->database();
	 
	 if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
	 if ($this->input->get('label') != '') $label = $this->input->get('label');
	 
	 /*$urlid = $_GET['urlid'];
	 $label = $_GET['label']; */
	 
	 $this->db->update('users_marks',array('tags'=>strtolower($label)),array('urlid' => $urlid,'userid'=>$this->session->userdata('userid')));
	 
	 // Success!
	 return;
	}
	
	public function addsmartlabel($domain='',$label='') {
	 if (!$this->session->userdata('userid')) { redirect('home'); }
	 $this->load->database();
	 
	 if ($this->input->get('domain') != '') $domain = $this->input->get('domain');
	 if ($this->input->get('label') != '') $label = $this->input->get('label');
	 
	 $noduplicates = $this->db->query("SELECT * FROM users_smartlabels WHERE userid = ".$this->session->userdata('userid')." AND domain = '".$domain."'");
	 
	 if ($noduplicates->num_rows() > 0) { // Update record
	   
	   $this->db->update('users_smartlabels',array('label'=>$label),array('domain'=>$domain,'userid'=>$this->session->userdata('userid')));
	   
	 } else { // Add new record
	   $this->db->insert('users_smartlabels',array('userid'=>$this->session->userdata('userid'),'domain'=>$domain,'label'=>$label));
	 }
	  return;
	}
	
	public function removesmartlabel($domain='',$label='') {
	 if (!$this->session->userdata('userid')) { redirect('home'); }
	 $this->load->database();
	 
	 if ($this->input->get('domain') != '') $domain = $this->input->get('domain');
	 if ($this->input->get('label') != '') $label = $this->input->get('label');
	 
	 $this->db->delete('users_smartlabels', array('userid' => $this->session->userdata('userid'),'domain'=>$domain));
	 
	 return;
	}
	
	public function checkdefaultsmartlabel($parsedUrl='') {
	 switch (str_replace('www.','',$parsedUrl['host'])) {
	   /* Video web services */
	   case 'youtube.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/watch');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'watch');
	     }
	   break;
	   
	   case 'viddler.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/v');
	     if ($pathPos !== FALSE) {
	        return array(TRUE,'watch');
	     }
	   break;
	   
	   case 'devour.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/video');
	     if ($pathPos !== FALSE) {
	        return array(TRUE,'watch');
	     }
	   break;
	   
	   case 'ted.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/talks');
	     if ($pathPos !== FALSE) {
	        return array(TRUE,'watch');
	     }
	   break;
	   
	   case 'vimeo.com':
	     return array(TRUE,'watch');
	   break;
	   
	   /* Documentation URLs */
	   case 'php.net':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/manual');
	     if ($pathPos !== FALSE) {
	        return array(TRUE,'read');
	     }
	   break;
	   
	   case 'api.rubyonrails.org':
	       return array(TRUE,'read');
	   break;
	   
	   case 'ruby-doc.org':
	       return array(TRUE,'read');
	   break;
	   
     case 'docs.jquery.com':
	       return array(TRUE,'read');
	   break;
	   
	   case 'codeigniter.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/user_guide');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'read');
	     }
	   break;
	   
	   case 'css-tricks.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/almanac');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'read');
	     }
	   break;
	   
	   case 'developer.apple.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/library');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'read');
	     }
	   break;
	   
	   /* Recipe URLs */
	   
	   case 'simplyrecipes.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'eatdrink');
	     }
	   break;
	   
	   case 'allrecipes.com':
	     return array(TRUE,'eatdrink');
	   break;
	   
	   case 'epicurious.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'eatdrink');
	     }
	   break;
	   
	   case 'foodnetwork.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'eatdrink');
	     }
	   break;
	   
	   case 'food.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/recipe');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'eatdrink');
	     }
	   break;
	   
	   /* Shopping URLs */

	   case 'svpply.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/item');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'buy');
	     }
	   break;   

	   case 'amazon.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/gp/product');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'buy');
	     }
	   break;

    case 'fab.com':
	     $pathPos = strpos(strtolower($parsedUrl['path']),'/sale');
	     if ($pathPos !== FALSE) {
	       return array(TRUE,'buy');
	     }
	   break;
	   
	   case 'zappos.com':
	     return array(TRUE,'buy');
	   break; 
	   
	   default:
	     //echo 'not adding any label';
	   break;
	 }
	 
	}
	
	public function addgroup($urlid='',$group='') {
	 
	 if (!$this->session->userdata('userid')) { redirect('home'); }
	 $this->load->database();
	 
	 if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
	 if ($this->input->get('group') != '') $group = $this->input->get('group');
	 
	 $this->db->update('users_marks',array('groups'=>$group),array('urlid' => $urlid,'userid'=>$this->session->userdata('userid')));
	 
	 // Duplicate this bookmark for every single person in the group.
	 $groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = ".$group);
	 
	 if ($groupmembers->num_rows() > 0) {
	   foreach($groupmembers->result_array() as $member) {
	     if ($member['userid'] != $this->session->userdata('userid')) {
	   
	       // No reason to duplicate the link. But, if the link is not yet in the group add it.
	       $link = $this->db->query("SELECT * FROM users_marks WHERE urlid = '".$urlid."' AND groups = '".$group."' AND userid = '".$member['userid']."'");
	       if ($link->num_rows() < 1) {
	         $this->db->insert('users_marks',array('userid'=>$member['userid'],'urlid'=>$urlid,'groups'=>$group,'addedby'=>$this->session->userdata('userid')));
	       }
	     }
	   }
	 }
	 
	 // Success!
	 return;
	}
	
  public function edit() {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->session->set_flashdata('lasturl', current_url());
	 
	 $this->load->database();
	 
	 $urlid = $this->uri->segment(3);
	 
   $mark = $this->db->query("SELECT * FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.id='".$urlid."'");
   
   if ($mark->num_rows() > 0) {
    $mark = $mark->result_array();
    
    $parsedUrl = parse_url($mark[0]['url']);
    
    // First, check for user smart labels
   $smartlabel = $this->db->query("SELECT * FROM users_smartlabels WHERE domain = '".strtolower($parsedUrl['host'])."' AND userid = '".$this->session->userdata('userid')."'");
   
   if ($smartlabel->num_rows() > 0) {  // smart label found
    $label = $smartlabel->row();
    $data['userlabeladded'] = TRUE;
   } else {
    // Figure out if it matches any default labels
  	 // Label accordingly.
  	 $smartlabel = $this->checkdefaultsmartlabel($parsedUrl);
  	 if ($smartlabel[0] == TRUE) {
  	   $data['labeladded'] = TRUE;
  	 }
   }
    
    $data['title'] = $mark[0]['title'];
    $data['url'] = $mark[0]['url'];
    $data['urlid'] = $mark[0]['urlid']; 
    $data['tags'] = $mark[0]['tags'];
    $data['note'] = $mark[0]['note'];
    $data['addedby'] = $mark[0]['addedby'];
    $data['groupid'] = $mark[0]['groups'];
    $data['urldomain'] = strtolower($parsedUrl['host']);
    
     $createdgroups = $this->db->query('SELECT * FROM groups WHERE createdby = '.$this->session->userdata('userid').' ORDER BY urlname asc');
   if ($createdgroups->num_rows() > 0) {
    $data['groups']['created'] = $createdgroups->result_array();
   }
    
   $belonggroups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid = '.$this->session->userdata('userid'));
   if ($belonggroups->num_rows() > 0) {
    $data['groups']['belong'] = $belonggroups->result_array();
   } 
   
    $data['label'] = '';
    $data['group']['groupuid'] = '';
    $data['when'] = 'all';
    
    
    $this->load->view('editpop',$data);
    } else {
     show_404();
   }
	}
	
	public function savenote($urlid='',$note='') {
	 
	 if (!$this->session->userdata('userid')) { redirect('home'); }
	 $this->load->database();
	 
	 if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
	 if ($this->input->get('note') != '') $note = $this->input->get('note');
	 
	 $this->db->update('users_marks',array('note'=>$note),"urlid = ".$urlid);
	 
	 // Success!
	 return;
	}
	
	public function archive() {
	
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->load->database();
	 
	 $id = $this->uri->segment(3);
	 
	 $this->db->update('users_marks',array('status'=>'archive'),array('id' => $id,'userid'=>$this->session->userdata('userid')));
	 
	 //$this->session->set_flashdata('message', 'Your mark has been successfully archived. To restore it, visit <a href="/home/archive">the archived marks list</a>.');
	 
	 if ($this->session->flashdata('lasturl')) {
	   echo 'success';
	   //redirect($this->session->flashdata('lasturl'));
	 } else {
	   echo 'success';
	   //redirect('home');
	 }
	 
	}
	
	public function restore() {
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 $this->load->database();
	 
	 $id = $this->uri->segment(3);
	 
	 $this->db->update('users_marks',array('status'=>''),array('id' => $id,'userid'=>$this->session->userdata('userid')));
	 
	 $this->session->set_flashdata('message', 'Your mark has been restored.');
	 $this->session->set_flashdata('restoredurlid',$urlid);
   
   redirect('home');
	 
	}
	
	// Finds the day's bookmarks
	// Checks to see if they need oEmbed
	// Process them.
	// Every 1 minute
	public function backprocessOembed() {
	 $this->load->database();
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
   $today = mktime(0, 0, 0, date('n'), date('j'));
   
   if (isset($_GET['all']) && $_GET['all'] == 'yes') {
    $marks = $this->db->query("SELECT * FROM marks WHERE oembed = ''ORDER BY id ASC LIMIT 100");
  } else {
    $marks = $this->db->query("SELECT * FROM marks WHERE UNIX_TIMESTAMP(dateadded) > ".$today." AND oembed = '' ORDER BY id ASC");
  }
   
   if ($marks->num_rows() > 0) {
      //print_r($marks->result_array()); exit;
      $numberofrecords = $marks->num_rows();
      $embedupdated = 0;
      foreach($marks->result_array() as $mark) {
        
        // OEmbed check
        $oembed = oembed($mark['url']);
        
        if (isset($oembed) && $oembed != '') {
          $embedupdated++;
          $this->db->update('marks',array('oembed'=>$oembed),array('id'=>$mark['id']));
        } else {
          $this->db->update('marks',array('oembed'=>'None'),array('id'=>$mark['id']));
        }
        
        $oembed = '';
      } // end foreach
      
      echo 'Bookmarks processed: '.$numberofrecords.'<br />Embeds added: '.$embedupdated;
   }
   
   
	 return;
	}
	
	// Finds the day's bookmarks
	// Checks to see if they need Recipe Parsing
	// Process them.
	// Every minute.
	public function backprocessRecipes() {
	 $this->load->database();
	 $this->load->helper('hrecipe');
	 
	 // Unix timestamps for yesterday and today
	 $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
   $today = mktime(0, 0, 0, date('n'), date('j'));
   
   if (isset($_GET['all']) && $_GET['all'] == 'yes') {
    $marks = $this->db->query("SELECT * FROM marks WHERE recipe = '' ORDER BY id ASC LIMIT 100");
   } else {
    $marks = $this->db->query("SELECT * FROM marks WHERE UNIX_TIMESTAMP(dateadded) > ".$today." AND recipe = '' ORDER BY id ASC");
   }
   
   if ($marks->num_rows() > 0) {
      //print_r($marks->result_array()); exit;
      $numberofrecords = $marks->num_rows();
      $embedupdated = 0;
      foreach($marks->result_array() as $mark) {
        
        // Recipe check
        if ($mark['url'] != 'http://localhost:8888/home') {
          $recipe = parse_hrecipe($mark['url']);
        }
        
        if (isset($recipe) && $recipe != '') {
          $embedupdated++;
          $this->db->update('marks',array('recipe'=>$recipe),array('id'=>$mark['id']));
        } else {
          $this->db->update('marks',array('recipe'=>'None'),array('id'=>$mark['id']));
        }
        
        $recipe = '';
      } // end foreach
      
      echo 'Bookmarks processed: '.$numberofrecords.'<br />Recipes added: '.$embedupdated;
   } else {
   
   echo 'No marks in the database need to be processed.';
   
   }
	 return;
	}
	
}

/* End of file nilai.php */
/* Location: ./application/controllers/nilai.php */