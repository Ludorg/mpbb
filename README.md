# mpbb Miniatures Painting Progess Bar (mppb)

Miniatures Paint Progess Bar (mppb) by Ludorg aka Adinarak (2008/04/05)

## 2020 foreword

This is some excavation from the past (found on my PC). This code was made when I was painting miniatures and maintaining a blog on this hobby: [La Horde d'Adinarak](http://war-lords.over-blog.com/).

## mppb usage (rtfm)

mppb is free software (beerware accepted). See (http://ludorg.net/adinarak/mppb.php?rtfm).

### What is mppb?

mppb is a php script inspired by [Graphiques d'avancement des figurines on 'Journal d'un pousseur de figurines](http://poussefigs.canalblog.com/archives/2005/12/10/1093251.html). This script generates a progress bar (png image) showing the achievement in the process of painting miniatures. This process has been divided in 10 steps of different lengths which are: 

- Step #1 is Buy the minis (Achat) and is 1% in miniature painting process
- Step #2 is needs translation (Dégrappage) and is 3% in miniature painting process
- Step #3 is needs translation (Ebavurage) and is 7% in miniature painting process
- Step #4 is needs translation (Assemblage) and is 7% in miniature painting process
- Step #5 is needs translation (Ensablage socle) and is 3% in miniature painting process
- Step #6 is needs translation (Sous-couche) and is 4% in miniature painting process
- Step #7 is Paint the minis (Peinture) and is 60% in miniature painting process
- Step #8 is needs translation (Peinture socle) and is 5% in miniature painting process
- Step #9 is needs translation (Herbage) and is 5% in miniature painting process
- Step #10 is needs translation (Vernis) and is 5% in miniature painting process

A half-painted miniature or a group of miniatures has not yet reached step 7 and is in step 6.5.

### Options

- step (mandatory)
- width : int
- height : int
- border : hide border (0/no)
- bg1_color : hex color (rgba) for the left part of bar
- bg2_color : hex color (rgba) the right part of bar
- rtfm : this help

### Using this script

```html
<img src="http://ludorg.net/adinarak/mppb.php?step=6.75&width=400&height=20">
```

## Examples

```html
http://ludorg.net/adinarak/mppb.php?step=3
```
![](http://ludorg.net/adinarak/mppb.php?step=3)

```html
http://ludorg.net/adinarak/mppb.php?step=6.8
```
![](http://ludorg.net/adinarak/mppb.php?step=6.8)

