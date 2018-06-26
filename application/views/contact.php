<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Clinc</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/contact.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/side-menu.css">
    <!-- <script type='text/javascript' src="<?php echo base_url(); ?>js/sketch.js"></script> -->
    <!-- <script type='text/javascript' src="<?php echo base_url(); ?>js/particles.js"></script> -->
    <script type='text/javascript' src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>js/side-menu.js"></script>
    <!-- <script type='text/javascript' src="<?php echo base_url(); ?>js/p5.js"></script> -->
</head>

<body>
  <!-- <header>
    <div id = "canvas" class="particle-background">
    </div>
    <div id="menu-icons">
      <div id="menu-line"></div>
    </div>

    <div class="header-logo">
      <img src="<?php echo base_url(); ?>/images/logo.png" height="57" width="200" />
    </div>

    <div class="intro">
      <h1>The Most Innovative, <br> Advanced Conversational AI for Finance</h1>
      <a href="" class="btn btn-one">Contact Us</a>
      <a href="" class="btn btn-two">About Us</a>
    </div>
  </header> -->

  <header>
    <div id="menu-icons">
      <div id="menu-line"></div>
    </div>
    <h1>Contact Clinc</h1>
    <p>The world's leading financial instituions are using Clinc's Conversational AI platform to redefine their customers' experiences.</p>
  </header>

  <section id="main-menu">
    <ul>
      <li><a href="<?php echo base_url(); ?>">HOME</a>
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>about">ABOUT</a>
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>career">CARREER
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>blog">BLOG
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>press">PRESS
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>services">SERVICES
        <div class="line"></div></a>
      </li>
      <li class="active"><a href="<?php echo base_url(); ?>contact">CONTACT
        <div class="active-line"></div></a>
      </li>
    </ul>
    <div id="close">
      <img src="<?php echo base_url(); ?>/images/close.png" />
    </div>
  </section>

<!-- <div class="col-md-6 col-md-offset-3">
    <div class="page-content"> -->
        <!-- <div class="panel-heading">
            <h3 class="panel-title">Contact Clinc</h3>
        </div> -->
      <section class="page-content">
        <div class="contact-container">
            <?php $attributes = array("name" => "Contact");
            echo form_open("Contact/index", $attributes);?>
            <div class="form-row">
            <div class="form-element">
                <label for="fname">First Name</label>
                <input class="form-control" name="fname" placeholder="Your First Name" type="text" value="<?php echo set_value('fname'); ?>" />
                <span class="text-danger"><?php echo form_error('fname'); ?></span>
            </div>
            <div class="form-element">
                <label for="lname">Last Name</label>
                <input class="form-control" name="lname" placeholder="Your Last Name" type="text" value="<?php echo set_value('lname'); ?>" />
                <span class="text-danger"><?php echo form_error('lname'); ?></span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-element">
                <label for="email">Email ID</label>
                <input class="form-control" name="email" placeholder="Email-ID" type="text" value="<?php echo set_value('email'); ?>" />
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>
            <div class="form-element">
                <label for="company_name">Company Name</label>
                <input class="form-control" name="company_name" placeholder="Your Company Name" type="text" value="<?php echo set_value('company_name'); ?>" />
                <span class="text-danger"><?php echo form_error('company_name'); ?></span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-element">
                <label for="job_title">Job Title</label>
                <input class="form-control" name="job_title" placeholder="Job Title" type="text" value="<?php echo set_value('job_title'); ?>" />
                <span class="text-danger"><?php echo form_error('job_title'); ?></span>
            </div>
            <div class="form-element">
                <label for="aboutus">How did you hear about us?</label>
                <input class="form-control" name="aboutus" placeholder="How did you hear about us?" type="text" value="<?php echo set_value('aboutus'); ?>" />
                <span class="text-danger"><?php echo form_error('aboutus'); ?></span>
            </div>
          </div>

            <div class="form-message">
                <label for="message">Message</label>
                <textarea class="form-control" name="message" rows="10" placeholder="Message"><?php echo set_value('message'); ?></textarea>
                <span class="text-danger"><?php echo form_error('message'); ?></span>
            </div>

            <div class="form-element">
                <input name="submit" type="submit" style="font-size: 16px;"></input>
            </div>
            <?php echo form_close(); ?>
            <?php echo $this->session->flashdata('msg'); ?>
        </div>
        <div class="contact-desc">
          <h3>Clinc Delivers</h3>
          <ul>
            <li>Scalable solutions that can support millions of users across channels </li>
            <li>Redefine customer experiences through conversational AI</li>
            <li>Reduce total cost ownership through faster time to market </li>
          </ul>
        </div>
      </section>
      <section class="page-content" id="location">
        <div class="location">
          <img src="<?php echo base_url(); ?>/images/contact/map.png" alt="">
          <h4>HEADQUARTERS</h4>
          <p>Headquarters in Ann Arbor, MI</p>
          <a href="#">206 E Huron St., Fl. 2, <br> Ann Arbor, MI 48104 </a>
        </div>
        <div class="location">
          <img src="<?php echo base_url(); ?>/images/contact/map.png" alt="">
          <h4>OFFICE</h4>
          <p>Office in Silicon Valley</p>
          <a href="#">424 Clay St.<br>San Francisco, CA 94111</a>
        </div>
      </section>
    <!-- </div>
</div> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
