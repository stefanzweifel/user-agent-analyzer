# User Agent Analyzer

[![Build Status](https://travis-ci.org/stefanzweifel/user-agent-analyzer.svg?branch=master)](https://travis-ci.org/stefanzweifel/user-agent-analyzer)
[![StyleCI](https://styleci.io/repos/55907025/shield)](https://styleci.io/repos/55907025)
[![Code Climate](https://codeclimate.com/github/stefanzweifel/user-agent-analyzer/badges/gpa.svg)](https://codeclimate.com/github/stefanzweifel/user-agent-analyzer)
[![Test Coverage](https://codeclimate.com/github/stefanzweifel/user-agent-analyzer/badges/coverage.svg)](https://codeclimate.com/github/stefanzweifel/user-agent-analyzer/coverage)

User Agent analysis and reporting as a Service.

![Screenshot of a Report](https://github.com/stefanzweifel/user-agent-analyzer/blob/master/public/images/screenshot-of-report.png?raw=true)

## What this?

From time to time I have to work with raw User Agent Data and most of the time I just need to know which device type a User Agent is. So I've created this very simple service which allows you to upload a CSV-File containing User Agent strings.
The service will then parse these User Agents (based on [serbanghita/Mobile-Detect](https://github.com/serbanghita/Mobile-Detect)), generate a report and emails you the results. 

You can download improved list of your uploaded User Agents on the result page.

## What about security?

- To prevent abuse of the system you currently have to provide a valid email address. Your address is [stored](https://github.com/stefanzweifel/user-agent-analyzer/blob/master/app/Models/Process.php#L38-L41) encrypted in our database.


## License

MIT