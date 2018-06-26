<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/services.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/side-menu.css">
  <script src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url(); ?>js/side-menu.js"></script>
  <title></title>
</head>

<body>

  <header>
    <div class="intro">
      <div id="menu-icons">
        <div id="menu-line"></div>
      </div>
      <img src="<?php echo base_url(); ?>images/logo.png" alt="" width="30%" style="padding: 50px;">
      <div class="intro-text">
        <h1>
            THE ENTERPRISE GRADE AI PLATFORM THAT SIMPLIFIES YOUR CUSTOMERS' LIVES
        </h1>
        <p>
          Clinc is the world leader in conversational AI research and its application for <br /> finance. We're on a mission to push the boundaries of conversational AI, <br /> empowering financial institutions to deliver a new and revolutionary AI experience
          for their customers.
        </p>
      </div>
    </div>
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
        <div class="line"></a></div>
      </li>
      <li><a href="<?php echo base_url(); ?>blog">BLOG
        <div class="line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>press">PRESS
        <div class="line"></div></a>
      </li>
      <li class="active"><a href="<?php echo base_url(); ?>services">SERVICES
        <div class="active-line"></div></a>
      </li>
      <li><a href="<?php echo base_url(); ?>contact">CONTACT
        <div class="line"></div></a>
      </li>
    </ul>
    <div id="close">
      <img src="<?php echo base_url(); ?>images/close.png" height="30px" width="30px" />
    </div>
  </section>

  <div class="content-container">
    <div class="page-content" id="page-heading">
      <div class="column">
        <div class="heading">
          <h2>Groundbreaking AI Technology</h2>
          <p>Our dynamic AI Engines employ the most advanced conversational technology on the market today.</p>
        </div>
        <div class="heading">
          <h2>Omni-Channel, Cross-Platform</h2>
          <p>Superpower your conversational experiences, including mobile, web, IVR, messengers, and chat bots.</p>
        </div>
      </div>
      <div class="column">
        <div class="heading">
          <h2>Fast, Simple and Secure Deployment</h2>
          <p>Integrate with Clinc using on-premise, private or public cloud installation and let our engineers do all the work.</p>
        </div>
        <div class="heading">
          <h2>Robust Suite of Management Tools</h2>
          <p>Complete with analytics, system administration tools, and a cutting-edge conversational AI training platform.</p>
        </div>
      </div>
    </div>
  </div>
    <div class="page-content" id="tech">
      <h1>COMPREHENSIVE AI TECHNOLOGY</h1>
      <div class="content">
        <div class="row">
          <div class="element" id="text">
            <h3>CLASSIFICATION AND SLOT-VALUE PAIRING</h3>
            <p>The Clinc AI Platform uses cutting-edge neural network to uncover important relationships between the words of a query and identifies a user’s intent with high confidence. It also extracts key pieces of information from a user’s query utterance, such as merchant names, specific accounts, and spendable items. The engine has been trained on a wide variety of examples, allowing it to learn how to understand utterances and extract information it has never even seen before.</p>
          </div>
          <div class="element" id="image">
            <img src="<?php echo base_url(); ?>images/services/v1.png" alt="">
          </div>
        </div>
      </div>

      <div class="content">
        <div class="row">
          <div class="element" id="text">
            <h3>WORD CONTEXT ANALYSIS</h3>
            <p>The Word Context Analysis engine transforms words into a high-dimensional mathematical space based on their semantic meaning. Working within this high-dimensional space allows our Word Context Analysis engine to perform mathematical operations on word representations, enabling the Clinc AI Platform to better understand the semantic similarity or dissimilarity between words and concepts. This engine frees our technology from being keyword-based, thus facilitating the use of unconstrained natural language when interacting with the system.</p>
          </div>
          <div class="element" id="image">
            <img src="<?php echo base_url(); ?>images/services/v2.png" alt="">
          </div>
        </div>
      </div>

      <div class="content">
        <div class="row">
          <div class="element" id="text">
            <h3>DATA-DRIVEN INSIGHTS</h3>
            <p>The Clinc AI Platform provides a Data Insights Analytics engine capable of extracting patterns and structure out of a user’s transactional data. This engine uses online machine learning and pattern recognition techniques to understand a user’s transaction history to deliver automated insights and analytics directly to the user.</p>
          </div>
          <div class="element" id="image">
            <img src="<?php echo base_url(); ?>images/services/v3.png" alt="">
          </div>
        </div>
      </div>

      <div class="content">
        <div class="row">
          <div class="element" id="text">
            <h3>CONVOLUTIONAL NEURAL NETWORK BASED SENTIMENT</h3>
            <p>Characterizes the emotion embedded in natural language to better understand customer attitude and mood when interacting with the system.</p>
          </div>
          <div class="element" id="image">
            <img src="<?php echo base_url(); ?>images/services/v4.png" alt="">
          </div>
        </div>
      </div>
    </div>

      <div class="content-container">
        <div class="feature-container">
          <h1>ROBUST SUITE OF MANAGEMENT TOOLS</h1>
          <div class="feature">
            <div class="column">
              <div class="heading">
                <h2>Understand Customer Behavior</h2>
                <p>Analytics dashboards and detailed query logs provide a holistic view of customer activity</p>
              </div>
              <div class="heading">
                <h2>Customize AI & User Experience</h2>
                <p>Customization tools segment your user base and allow personalization of user experience</p>
              </div>
            </div>
            <div class="column">
              <div class="heading">
                <h2>Continually Improve Your AI</h2>
                <p>Add new competencies with our comprehensive training platform, complete with crowdsourcing tools</p>
              </div>
              <div class="heading">
                <h2>On-Site Training</h2>
                <p>Our engineering team delivers workshops and tutorials right to you, maximizing the impact of the Clinc AI Platform for your institution</p>
              </div>
            </div>
          </div>
        </div>
      </div>


  </div>
</body>

</html>
