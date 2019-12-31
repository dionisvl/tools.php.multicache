#php cache testing


## about 
tool for speed test caching tools on current machine  
supported caching instruments:
- file
- mysqli + persistent variant
- pdo mysql + persistent variant
- predis
## instalation
- git clone git@github.com:dionisvl/tools.php.multicache.git 
- composer install


test DB name - test  
test table name - test

## using
- for simple run:  
`mysite.com/pets/anycache/examples_tests/`

- for persistent connection:   
`mysite.com/pets/anycache/examples_tests?persistent=1`
