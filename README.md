# Debugger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Debugger fornisce strumenti di supporto per il controllo dei dati all'interno di un database.

Attraverso Debugger è possibile ricevere informazioni sul corretto funzionamento dell'applicativo web, in particolare Debugger fornisce una serie di metodi per controllare l'integrita del database collegato.

Debugger legge una serie di query di test, le query devono restituire le righe corrispondenti agli errori nel database. 
Se la query contiene zero righe allora non sono presenti errori.

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practices by being named the following.

```
bin/        
build/
docs/
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require AndreFra96/Debugger
```

## Usage

``` php
$debugger = new AndreFra96\Debugger();
if($debugger->connect("servername","username","password","dbname")){
    if(!($debugger->debugSpecific($testid)))
        $bugs = $debugger->debugData($testid);
}

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email andrea.brioschi@rcamilano.it instead of using the issue tracker.

## Credits

- [Andrea Francesco Brioschi][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/AndreFra96/Debugger.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/AndreFra96/Debugger/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/AndreFra96/Debugger.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/AndreFra96/Debugger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/AndreFra96/Debugger.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/AndreFra96/Debugger
[link-travis]: https://travis-ci.org/AndreFra96/Debugger
[link-scrutinizer]: https://scrutinizer-ci.com/g/AndreFra96/Debugger/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/AndreFra96/Debugger
[link-downloads]: https://packagist.org/packages/AndreFra96/Debugger
[link-author]: https://github.com/AndreFra96
[link-contributors]: ../../contributors
