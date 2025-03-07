### Введение

Шаблон Twig это текстовый файл, который может генерировать любой формат, основанный на тексте (HTML, XML, CSV, LaTeX, ...).
Twig устанавливает два вида разделителей:

+ {{ ... }}: Выводит переменную или результат выражения в шаблон;
+ {% ... %}: Тег, управляющий логикой шаблона; например, используется для выполнения for циклов или if условий.


### Наследование

Шаблон index.twig.html наследуется от layout.twig.html, спасибо тегу extends:

	{% extends "HelloBundle::layout.twig.html" %}

	{% block body %}
		{% include "HelloBundle:Hello:hello.twig.html" %}
	{% endblock %}
	
Обозначение HelloBundle::layout.twig.html выглядит знакомо, не так ли?
Обозначается так же как ссылка на обычный шаблон. Эта часть :: всего лишь обозначает что контроллер не указан,
т. о. соотвествующий файл хранится прямо в views/.
HelloBundle:Hello:hello.twig.html - это шаблон, включаемый "внутрь".


### Вложение других контроллеров

Что если вы захотите вложить результат другого контроллера в шаблон?
Это очень удобно когда работаешь с Ajax или когда встроенному шаблону необходимы переменные,
которые не доступны в главном шаблоне.

Если вы создали действие fancy и хотите включить его в шаблон index, используйте тег render:

	{# src/Application/HelloBundle/Resources/views/Hello/index.twig.html #}
	{% render "HelloBundle:Hello:fancy" with { 'name': name, 'color': 'green' } %}
	
### Внутренние ссылки

Говоря о web приложениях, нельзя не упомянуть о ссылках. Вместо жёстких URL-ов в шаблонах, функция path поможет сделать URL-ы, основанные на конфигурации маршрутизатора. Таким образом URL-ы могут быть легко обновлены, если изменить конфигурацию:

	<a href="{{ path('hello', { 'name': 'Thomas' }) }}">Greet Thomas!</a>
	

### Подключение активов

Как выглядел бы интернет без изображений, JavaScript-ов и таблиц стилей? Symfony2 предлагает функцию asset для работы с ними:

	<link href="{{ asset('css/blog.css') }}" rel="stylesheet" type="text/css" />
	<img src="{{ asset('images/logo.png') }}" />