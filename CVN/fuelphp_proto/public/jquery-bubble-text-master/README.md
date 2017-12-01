# jquery-bubble-text
This plugin introduces one animated way of changing text.

- [Examples](#examples)
- [Properties](#properties)
- [Methods](#methods)


# Examples

1. Fading letters. [(jsFiddle)](https://jsfiddle.net/tkppkaek/)
2. Looping phrases. [(jsFiddle)](https://jsfiddle.net/ncs3uagj/)


# Properties

### Mandatory: element, and newText.

There are only two properties you must supply, so it can work:

```JavaScript
var properties = {
    element: $element,
    newText: 'new Text',
};
bubbleText(properties);
```

- `properties.element` is the DOM node in which the animation will occur and must be a leaf one (without children) because spans will run inside it during animation phase.
- `properties.newText` must be a string.

Below you can see the optional properties:

### speed

The default speed from start till the end is 2000 miliseconds, you can change it this way:

```JavaScript
properties.speed = 3500;  // 3.5 seconds
```

### letterSpeed

This property has priority over `properties.speed`. If you choose setting `letterSpeed`, the total time of the animations will be proportional to its old and new text lengths.

```JavaScript
properties.letterSpeed = 50;  // miliseconds
```

### callback

You can execute a function after the animation is completed:

```JavaScript
properties.callback = function() {
    console.log('finished');
};
```

### proportional

The proportional property is set true by default, if the initial text is `"abcd"` and the new text is `"ef"`, the animation will be: 

1. remove `"a"`, 
2. remove `"b"`, 
3. add `"e"`, 
4. remove `"c"`, 
5. remove `"d"`, 
6. add `"f"`. 

But some may prefer steps of 1 remotion and 1 addition:

1. remove `"a"`, 
2. add `"e"`, 
3. remove `"b"`, 
4. add `"f"`. 
5. remove `"c"`, 
6. remove `"d"`, 

For that purpose you can use:

```JavaScript
properties.proportional = false;
```

### repeat

The `repeat` action is `0`, by default it runs the animation only once. But you can repeat one animation again and again if you want so:

```JavaScript
properties.repeat = 2;
```

Since `Infinity - 1` results `Infinity`, you can do an endless animation with:

```JavaScript
properties.repeat = Infinity;
```

### timeBetweenRepeat

The previous `repeat` property has a default delay of 1.5 seconds before starting again, but you can change it:

```JavaScript
properties.timeBetweenRepeat = 500;  // 0.5 seconds
```


# Methods

The `bubbleText` function returns an instance control to you:

```JavaScript
var ctrl = bubbleText(options);
```

This instance have three methods you can use:

### restart

If you don't want to wait the end of the animation to restart with `repeat` or `callback`, you can use:

```JavaScript
ctrl.restart();
```

This method:

- will instantly set the old text back;
- will do the animation again.

### finish

If for some strange weird reason you need to finish the animation in a shot, you can use:

```JavaScript
ctrl.finish();
```

This method:

- will instantly set the new text to the $element (without spans);
- will not restart the animation even with `properties.repeat` on;
- accepts a boolean argument (when true will run the `properties.callback` function).

### stop

If for some other strange weird reason you need to stop the animation, you can use:

```JavaScript
ctrl.stop();
```

This method:

- will stop the spans immediately;
- will not remove the spans;
- will not trigger `properties.callback`.

You are able to call `ctrl.restart` or `ctrl.finish` after `ctrl.stop()`.


# Happy coding

That's it !! Thanks for your interest :)

Guedes, Washington.
