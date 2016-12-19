### Resolver

```{.php}
<?php

use Chubbyphp\Model\Resolver;
use Interop\Container\ContainerInterface;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;

$container = ...

$resolver = new Resolver($container, [MyRepository::class]);
$resolver->find(MyModel::class, 5);
```
