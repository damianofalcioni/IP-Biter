# VSCode Setup

### Windows Instructions
1) Download PHP from https://windows.php.net/download and extract in a folder of choice
2) Download XDebug from https://xdebug.org/download selecting the right version for your PHP
3) Copy the downloaded XDebug DLL in the PHP `ext` folder and rename it to `php_xdebug.dll`
4) Rename the `php.ini-development` file in the PHP folder as `php.ini`
5) Edit the `php.ini` file applying the following changes:
    - uncomment `extension_dir = "ext"` 
    - uncomment `zend_extension=xdebug`
    - Add the following lines at the end of the file:
        ```
        [XDebug]
        xdebug.mode = debug
        xdebug.start_with_request = yes
        ```
6) Add the PHP path to system variables
7) Test the correct installation executing `php -v` from a command console. The output should be something like:
    ```
    PHP 8.2.5 (cli) (built: Apr 12 2023 08:43:43) (ZTS Visual C++ 2019 x64)
    Copyright (c) The PHP Group
    Zend Engine v4.2.5, Copyright (c) Zend Technologies
        with Xdebug v3.2.1, Copyright (c) 2002-2023, by Derick Rethans
    ```
8) Install VSCode Extension `PHP Debug` from `Xdebug`
9) Launch the `Build-in web server` in the VSCode debug configuration