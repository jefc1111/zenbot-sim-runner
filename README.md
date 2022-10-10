<h1>This project is no longer active</h1>

<p align="center">
  <a href="https://github.com/jefc1111/zenbot-sim-runner">
    <img src="readme_images/logo_200x200.png" alt="Logo" width="300" height="300">
  </a>
  <p align="center">
    A sim run batch aggregator / automator for <a href="https://github.com/DeviaVir/zenbot">Zenbot</a>. Eases the process of backtesting and subsequent analysis of results.
  </p>
  <hr />
  <h3>
    The documentation below is very out of date at the moment as I do not have time to update it or support a documented deployment guide.
  </h3>
  <h3>
    If you are interested in this project please contact me here in Github or on <a target="_blank" href="https://discord.com/channels/880347822854115328/880347822854115331">Discord</a>.
  </h3>
  <hr />
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
        <li><a href="#installation">Installation</a><strong> - work in progress</strong></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a><strong> - work in progress</strong></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgements">Acknowledgements</a></li>
  </ol>
</details>

## About The Project
This application is a companion to the cryptocurrency trading bot <a href="https://github.com/DeviaVir/zenbot">Zenbot</a>. Zenbot Sim Runner is able to import various data from Zenbot and then automate the running of simulations across multiple variations of multiple strategies.

I built this because:
* Testing and refining strategies might be a key component in an individual's journey towards any kind of live trading.
* Zenbot's built in default functionality allows running of only simulation at a time, so tweaking parameters for comparison is laborious.
* Zenbot stores simulation results in a format which does not easily allow comparison across multiple simulations. 

This was primarily built for my own use and so is super-janky in places! There are no tests, no form validation, etc etc, so errors and bugs at this stage are to be expected. 

Zenbot itself has disclaimers that should suffice, but just to be sure the message gets across: Use this project and Zenbot <strong>AT YOUR OWN RISK</strong>. You can and probably will lose money if and when you live trade on an exchange.

### Built With

This project is basded on the PHP framework Laravel. In particular, it uses Laravel's job queue functionality to allow queueing up controlled submission of many simulation runs in one batch. 
* [Laravel](https://laravel.com)
* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)
* [Datatables](https://datatables.net/manual/)
* [Highcharts](https://www.highcharts.com/)


## Getting Started

`docker-compose up` then work through all the issues that arise! (seriously, if you are trying this then just give me a shout if you get stuck)

### Prerequisites

* Zenbot  
  This project is meaningless without it!  
  https://github.com/DeviaVir/zenbot
* MySQL
* Redis  
  
### Installation

See 'Getting Started'

## Usage

Work in progress

1. Import strategies, exchanges and products from Zenbot (this populates the corresponding MySQL tables)
2. Create a sim run batch (select strategies, refine strategies, confirm)
3. Run the batch, or individual sim runs
4. Observe queued jobs
5. View results
6. Copy batch details

## License

Distributed under the MIT License.

## Contact

Discord: [https://discord.com/channels/880347822854115328/880347822854115331](https://discord.com/channels/880347822854115328/880347822854115331)

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
[product-screenshot-1]: readme_images/screenshots_290421/1_new_sim_run_batch.png
[product-screenshot-2]: readme_images/screenshots_290421/2_select_strategies.png
[product-screenshot-3]: readme_images/screenshots_290421/3_refine_strategies.png
[product-screenshot-4]: readme_images/screenshots_290421/4_review_sim_runs.png
[product-screenshot-5]: readme_images/screenshots_290421/5_batch_ready_to_run.png
[product-screenshot-6]: readme_images/screenshots_290421/6_batch_results.png
[product-screenshot-7]: readme_images/screenshots_290421/7_results_of_one_sim_run.png
[product-screenshot-8]: readme_images/screenshots_250521/1_batch_analysis_chart.png
[product-screenshot-9]: readme_images/screenshots_250521/2_batch_family_tree.png














