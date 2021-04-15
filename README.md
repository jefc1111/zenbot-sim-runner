<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
<!--
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]
-->
<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/jefc1111/zenbot-sim-runner">
    <img src="readme_images/logo_200x200.png" alt="Logo" width="200" height="200">
  </a>

  <h3 align="center">Zenbot Sim Runner</h3>

  <p align="center">
    A sim run automator for <a href="https://github.com/DeviaVir/zenbot">Zenbot</a>
    <br />
    ·
    <a href="https://github.com/jefc1111/zenbot-sim-runner/issues">Report Bug</a>
    ·
    <a href="https://github.com/jefc1111/zenbot-sim-runner/issues">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgements">Acknowledgements</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

This application is a companion to the cryptocurrency trading bot <a href="https://github.com/DeviaVir/zenbot">Zenbot</a>. Zenbot Sim Runner is able to import various data from Zenbot and then automate the running of simulations across multiple variations of multiple strategies.

I built this because:
* Testing and refining strategies is key to profitable use of a trading bot.
* Zenbot's built in default functionality allows running of only simulation at a time, so tweaking parameters for comparison is laborious.
* Zenbot stores simulation results in a format which does not easily allow comparison across multiple simulations. 

This was primarily built for my own use and so is super-janky in places! There are no tests, no form validation, etc etc, so errors and bugs at this stage are to be expected. 

Zenbot itself has disclaimers that should suffice, but just to be sure the message gets across: Use this project and Zenbot <strong>AT YOUR OWN RISK</strong>. You can and probably will lose money if and when you live trade on an exchange.

### Built With

This project leverages the excellent PHP framework Laravel. In particular, it uses Laravel's job queue functionality to allow queueing up controlled submission of many simulation runs in one batch. 
* [Laravel](https://laravel.com)
* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)

<!-- GETTING STARTED -->
## Getting Started

This section is a work in progress :)

I am running this on Manjaro Linux but it doesn't have any very exotic dependencies so it should run on any mainstream OS.  

in a nutshell, you need to install the dependencies, tell it where your working instance of Zenbot is, import some data from Zenbot and away you go!

### Prerequisites

* Zenbot  
  This project is meaningless without it!  
  https://github.com/DeviaVir/zenbot
* npm  
  You probably already have npm installed if you have a working copy of Zenbot!
* composer (PHP dependency manager)  
  https://getcomposer.org/download/
* MySQL / MariaDB  
  I installed MariaDB on Manjaro. You may prefer to use a db in the cloud, or MySQL on Ubuntu etc etc. You could probably use MSSQL Server and maybe others because Laravel provides a layer of abstraction between the DB and the app code. I have only tested wiyth MariaDB.  
* Redis  
  https://redis.io/topics/quickstart

*Note:* Zenbot Sim Runner does not deal with backfilling - you need to do this directly from Zenbot before running any relevant sim runs.

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/jefc1111/zenbot-sim-runner.git
   ```
2. Install NPM packages
   ```sh
   npm install
   ```
3. Install composer packages 
   ```sh
   composer install
   ```
4. Build front end bundle  
   ```sh
   npm run dev
   ```
5. Create database tables
   ```sh
   php artisan migrate
   ```
6. Start the app using PHP's built in web server (alternatively you could run it on an Apache or nginx web server)
   ```sh
   php artisan serve
   ```


<!-- USAGE EXAMPLES -->
## Usage

Work in progress

1. Import strategies, exchanges and products from Zenbot (this populates the corresponding MySQL tables)
2. Create a sim run batch (select strategies, refine strategies, confirm)
3. Run the batch, or individual sim runs
4. Observe queued jobs
5. View results
6. Copy batch details



<!-- ROADMAP -->
## Roadmap

[Project Trello board](https://trello.com/b/xlTinWNf/zenbot-sim-runner)



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

Geoff - jefc_uk@hotmail.com

Project Link: [https://github.com/jefc1111/zenbot-sim-runner](https://github.com/jefc1111/zenbot-sim-runner)



<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
* [Zenbot](https://github.com/DeviaVir/zenbot)
* [Laravel](https://laravel.com/)
* [Img Shields](https://shields.io)
* [Choose an Open Source License](https://choosealicense.com)
* [Best README Template](https://github.com/othneildrew/Best-README-Template)
* [Bootstrap](https://getbootstrap.com/)





<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/othneildrew/Best-README-Template.svg?style=for-the-badge
[contributors-url]: https://github.com/jefc1111/zenbot-sim-runner/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/othneildrew/Best-README-Template.svg?style=for-the-badge
[forks-url]: https://github.com/jefc1111/zenbot-sim-runner/network/members
[stars-shield]: https://img.shields.io/github/stars/othneildrew/Best-README-Template.svg?style=for-the-badge
[stars-url]: https://github.com/jefc1111/zenbot-sim-runner/stargazers
[issues-shield]: https://img.shields.io/github/issues/othneildrew/Best-README-Template.svg?style=for-the-badge
[issues-url]: https://github.com/jefc1111/zenbot-sim-runner/issues
[license-shield]: https://img.shields.io/github/license/othneildrew/Best-README-Template.svg?style=for-the-badge
[license-url]: https://github.com/jefc1111/zenbot-sim-runner/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/geoff-clayton-b0222982/
[product-screenshot]: readme_images/main_screenshot.png
