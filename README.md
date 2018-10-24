Sonar
=====

Backend technical challenge for Hyra iQ.

## Task

Implement a console application to generate a payslip.

- The [PayslipGenerator](src/Services/PayslipGenerator.php) must be implemented
- There are a few tests for it already, most of which fail: [PayslipGeneratorTest](tests/Services/PayslipGeneratorTest.php)
    - Make sure to stop marking the failing tests an `incomplete`
- The generator is passed two objects:
    - PayConfig:
        - Base hourly rate that employees earn
        - Regular hours an employee can work, any hours over this count as overtime
        - Rate multiplier for any overtime hours
        - A list of ShiftTypes:
            - Name of the type, ie. ordinary, weekend, etc
            - Rate multiplier for this shift type, ie. penalty rates
        - A list of TaxTypes:
            - Name of the type, ie. PAYG, HECS HELP, etc
            - Tax rate
            - Earnings threshold
                - The tax is only applied when the employees earnings are greater than the threshold
    - EmployeeTimesheet:
        - List of SuperFunds:
            - Name of the fund to pay the superannuation to
            - Percentage of wage that is paid into super (pre-tax)
        - List of Shifts
            - Type of shift - comes from the ShiftType in the PayConfig
            - Number of hours the shift was
- Some example configs are available in [config/resources](config/resources) but you should create new more complex
    configs for your own testing
- The PayslipGenerator must return a Payslip which is:
    - The gross pay for the employee
    - The total tax deductions that come out of the pay
    - One of each of the following Sections:
        - An EarningsSection consists of a number of EarningsItems and details the pay an employee gets
        - A SuperannuationSection details the super that is paid on behalf of the employee:
            - Gross pay paid to the employee
            - A SuperannuationItem for each SuperFund of the employee
        - A TaxSection details the amount of Tax withheld from the employee's pay
            - Gross pay paid to the employee
            - A TaxItem for each TaxType listed in the PayConfig
- You shouldn't need to modify any of the code provided (apart from the PayslipGenerator and it's tests), but feel free
    to do so if you want
- The PayslipGenerator needs to meet the requirements listed below


### Requirements

#### Generating Earnings

1. If an employee works less than the regular hours, they are paid for each shift by the `shift hours * shift rate`
2. Any shifts which put the employee's hours over the regular hours have their rate increased by the overtime multiplier
3. Employees are only paid overtime for the parts of a shift which fall over the regular hours
4. There should be a single EarningsItem in the Payslip for every shift, unless the shift gets broken by overtime
5. If a shift is broken by overtime, there should be two separate EarningsItems for it
6. Any shifts that earn overtime, should be marked as such in the Payslip

#### Generating Tax

1. Tax is only applied if the employlee's earnings are above the threshold
2. If the earnings are above the threshold, tax is applied to the entire earnings
3. Tax is applied to the Payslip as a deduction
                
#### Generating Superannuation

1. All SuperannuationItems are generating from the employee's before-tax income
2. Super is not counted as either an earning or a deduction on the Payslip

## Background

We use the [Symfony Framework](https://symfony.com/doc/current/index.html) on top of PHP 7.2 at Hyra iQ and we have a 
strong focus on code quality, testing and type safety. 

What we're are looking for:

- Well structured code
- Well tested code
- An ability to break down problems into simple solutions
- Determination and perseverance for solving difficult problems

What we're not testing:

- Knowledge of Symfony/PHP

What we've provided:

- Development environment
- Most of the foundation code that interacts with the framework


## Running the application

### Prerequisites:

You'll need:

- [Vagrant](https://www.vagrantup.com/)
- [Virtualbox](https://www.virtualbox.org/)
- A private GitHub repo
    - [$7/month](https://github.com/pricing)
    - If you're a student, you can [get it for free](https://help.github.com/articles/applying-for-a-student-developer-pack/)
    
### Setting up the application

Vagrant will automatically create a virtual machine for you, and install all dependencies. You just need to start the VM:

```bash
$ vagrant up
```

### Using the application

Once the VM is running, you can log into with:

```bash
$ vagrant ssh
```

We then have some simple bash aliases set up to make things easier:

- `cs` - `c`hange to the `s`ource directory (/srv/www/app/current)
    - You'll need to be in this directory to run the code
- `xon`/`xoff` - Turn xdebug on and off
    - Useful for setting breakpoints for debugging
    - Will generate code coverage when tests are run
- `cc` - Clear the Symfony cache
    - If something isn't working, try this first

You can run the current tests with (code coverage will be generated in `build/phpunit/coverage`):

```bash
$ make test
```

To run the application you will be running a Symfony command:

```bash
./bin/console app:payslip config/resources/payConfig.yml config/resources/timesheet.yml
```

This will execute the [PaySlipCommand](src/Command/PayslipCommand.php) passing in the payment configuration and an
employees timesheet/superannuation details.

## Resources
- [Symfony console documentation](https://symfony.com/doc/current/components/console.html)
- [PHPUnit documentation](https://phpunit.de/documentation.html)
- [PHP-CS-Fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer)
