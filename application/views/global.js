
//Emoji Picker:
const EmojiPicker=function(e){this.options=e,this.trigger=this.options.trigger.map(e=>e.selector),this.insertInto=void 0;let i="",t="",o,l=!1,a=this.options.closeButton?370:350;this.lib=function(e){let i=e=>{var i=Object.prototype.toString.call(e);return"object"==typeof e&&/^\[object (HTMLCollection|NodeList|Object)\]$/.test(i)&&"number"==typeof e.length&&(0===e.length||"object"==typeof e[0]&&e[0].nodeType>0)};return{el(){if(e)return e.nodeName?[e]:i(e)?Array.from(e):"string"==typeof e||"STRING"==typeof e?Array.from(document.querySelectorAll(e)):void 0},on(e,i,t){t?this.el().forEach(o=>{o.addEventListener(e,e=>{if(e.target.closest(t)){let o;if(Array.isArray(t)){let l=e.target.outerHTML,a=t.findIndex(e=>l.includes(e.slice(1)));o=t[a]}i(e,o)}})}):this.el().forEach(t=>{t.addEventListener(e,i.bind(t))})},css(e){for(let i in e)if(Object.hasOwnProperty.call(e,i)){let t=e[i];this.el().forEach(e=>e.style[i]=t)}},attr(e,i){if(!i)return this.el()[0].getAttribute(e);this.el().forEach(t=>t.setAttribute(e,i))},removeAttr(e){this.el().forEach(i=>i.removeAttribute(e))},addClass(e){this.el().forEach(i=>i.classList.add(e))},removeClass(e){this.el().forEach(i=>i.classList.remove(e))},slug:e=>e.toLowerCase().replace(/[^\u00BF-\u1FFF\u2C00-\uD7FF\w]+|[\_]+/ig,"-").replace(/ +/g,"-"),remove(e){this.el().forEach(e=>e.remove())},val(e){let i;return void 0===e?this.el().forEach(e=>{i=e.value}):this.el().forEach(i=>{i.value=e}),i},text(i){if(void 0===i)return e.innerText;this.el().forEach(e=>{e.innerText=i})},html(i){if(void 0===i)return e.innerHTML;this.el().forEach(e=>{e.innerHTML=i})}}};let m={People:[{emoji:"\uD83D\uDE00",title:"Grinning Face"},{emoji:"\uD83D\uDE03",title:"Grinning Face with Big Eyes"},{emoji:"\uD83D\uDE04",title:"Grinning Face with Smiling Eyes"},{emoji:"\uD83D\uDE01",title:"Beaming Face with Smiling Eyes"},{emoji:"\uD83D\uDE06",title:"Grinning Squinting Face"},{emoji:"\uD83D\uDE05",title:"Grinning Face with Sweat"},{emoji:"\uD83E\uDD23",title:"Rolling on the Floor Laughing"},{emoji:"\uD83D\uDE02",title:"Face with Tears of Joy"},{emoji:"\uD83D\uDE42",title:"Slightly Smiling Face"},{emoji:"\uD83D\uDE43",title:"Upside-Down Face"},{emoji:"\uD83D\uDE09",title:"Winking Face"},{emoji:"\uD83D\uDE0A",title:"Smiling Face with Smiling Eyes"},{emoji:"\uD83D\uDE07",title:"Smiling Face with Halo"},{emoji:"\uD83E\uDD70",title:"Smiling Face with Hearts"},{emoji:"\uD83D\uDE0D",title:"Smiling Face with Heart-Eyes"},{emoji:"\uD83E\uDD29",title:"Star-Struck"},{emoji:"\uD83D\uDE18",title:"Face Blowing a Kiss"},{emoji:"\uD83D\uDE17",title:"Kissing Face"},{emoji:"☺️",title:"Smiling Face"},{emoji:"\uD83D\uDE1A",title:"Kissing Face with Closed Eyes"},{emoji:"\uD83D\uDE19",title:"Kissing Face with Smiling Eyes"},{emoji:"\uD83E\uDD72",title:"Smiling Face with Tear"},{emoji:"\uD83D\uDE0B",title:"Face Savoring Food"},{emoji:"\uD83D\uDE1B",title:"Face with Tongue"},{emoji:"\uD83D\uDE1C",title:"Winking Face with Tongue"},{emoji:"\uD83E\uDD2A",title:"Zany Face"},{emoji:"\uD83D\uDE1D",title:"Squinting Face with Tongue"},{emoji:"\uD83E\uDD11",title:"Money-Mouth Face"},{emoji:"\uD83E\uDD17",title:"Smiling Face with Open Hands"},{emoji:"\uD83E\uDD2D",title:"Face with Hand Over Mouth"},{emoji:"\uD83E\uDD2B",title:"Shushing Face"},{emoji:"\uD83E\uDD14",title:"Thinking Face"},{emoji:"\uD83E\uDD10",title:"Zipper-Mouth Face"},{emoji:"\uD83E\uDD28",title:"Face with Raised Eyebrow"},{emoji:"\uD83D\uDE10",title:"Neutral Face"},{emoji:"\uD83D\uDE11",title:"Expressionless Face"},{emoji:"\uD83D\uDE36",title:"Face Without Mouth"},{emoji:"\uD83D\uDE36‍\uD83C\uDF2B️",title:"Face in Clouds"},{emoji:"\uD83D\uDE0F",title:"Smirking Face"},{emoji:"\uD83D\uDE12",title:"Unamused Face"},{emoji:"\uD83D\uDE44",title:"Face with Rolling Eyes"},{emoji:"\uD83D\uDE2C",title:"Grimacing Face"},{emoji:"\uD83D\uDE2E‍\uD83D\uDCA8",title:"Face Exhaling"},{emoji:"\uD83E\uDD25",title:"Lying Face"},{emoji:"\uD83D\uDE0C",title:"Relieved Face"},{emoji:"\uD83D\uDE14",title:"Pensive Face"},{emoji:"\uD83D\uDE2A",title:"Sleepy Face"},{emoji:"\uD83E\uDD24",title:"Drooling Face"},{emoji:"\uD83D\uDE34",title:"Sleeping Face"},{emoji:"\uD83D\uDE37",title:"Face with Medical Mask"},{emoji:"\uD83E\uDD12",title:"Face with Thermometer"},{emoji:"\uD83E\uDD15",title:"Face with Head-Bandage"},{emoji:"\uD83E\uDD22",title:"Nauseated Face"},{emoji:"\uD83E\uDD2E",title:"Face Vomiting"},{emoji:"\uD83E\uDD27",title:"Sneezing Face"},{emoji:"\uD83E\uDD75",title:"Hot Face"},{emoji:"\uD83E\uDD76",title:"Cold Face"},{emoji:"\uD83E\uDD74",title:"Woozy Face"},{emoji:"\uD83D\uDE35",title:"Face with Crossed-Out Eyes"},{emoji:"\uD83D\uDE35‍\uD83D\uDCAB",title:"Face with Spiral Eyes"},{emoji:"\uD83E\uDD2F",title:"Exploding Head"},{emoji:"\uD83E\uDD20",title:"Cowboy Hat Face"},{emoji:"\uD83E\uDD73",title:"Partying Face"},{emoji:"\uD83E\uDD78",title:"Disguised Face"},{emoji:"\uD83D\uDE0E",title:"Smiling Face with Sunglasses"},{emoji:"\uD83E\uDD13",title:"Nerd Face"},{emoji:"\uD83E\uDDD0",title:"Face with Monocle"},{emoji:"\uD83D\uDE15",title:"Confused Face"},{emoji:"\uD83D\uDE1F",title:"Worried Face"},{emoji:"\uD83D\uDE41",title:"Slightly Frowning Face"},{emoji:"☹️",title:"Frowning Face"},{emoji:"\uD83D\uDE2E",title:"Face with Open Mouth"},{emoji:"\uD83D\uDE2F",title:"Hushed Face"},{emoji:"\uD83D\uDE32",title:"Astonished Face"},{emoji:"\uD83D\uDE33",title:"Flushed Face"},{emoji:"\uD83E\uDD7A",title:"Pleading Face"},{emoji:"\uD83D\uDE26",title:"Frowning Face with Open Mouth"},{emoji:"\uD83D\uDE27",title:"Anguished Face"},{emoji:"\uD83D\uDE28",title:"Fearful Face"},{emoji:"\uD83D\uDE30",title:"Anxious Face with Sweat"},{emoji:"\uD83D\uDE25",title:"Sad but Relieved Face"},{emoji:"\uD83D\uDE22",title:"Crying Face"},{emoji:"\uD83D\uDE2D",title:"Loudly Crying Face"},{emoji:"\uD83D\uDE31",title:"Face Screaming in Fear"},{emoji:"\uD83D\uDE16",title:"Confounded Face"},{emoji:"\uD83D\uDE23",title:"Persevering Face"},{emoji:"\uD83D\uDE1E",title:"Disappointed Face"},{emoji:"\uD83D\uDE13",title:"Downcast Face with Sweat"},{emoji:"\uD83D\uDE29",title:"Weary Face"},{emoji:"\uD83D\uDE2B",title:"Tired Face"},{emoji:"\uD83E\uDD71",title:"Yawning Face"},{emoji:"\uD83D\uDE24",title:"Face with Steam From Nose"},{emoji:"\uD83D\uDE21",title:"Enraged Face"},{emoji:"\uD83D\uDE20",title:"Angry Face"},{emoji:"\uD83E\uDD2C",title:"Face with Symbols on Mouth"},{emoji:"\uD83D\uDE08",title:"Smiling Face with Horns"},{emoji:"\uD83D\uDC7F",title:"Angry Face with Horns"},{emoji:"\uD83D\uDC80",title:"Skull"},{emoji:"☠️",title:"Skull and Crossbones"},{emoji:"\uD83D\uDCA9",title:"Pile of Poo"},{emoji:"\uD83E\uDD21",title:"Clown Face"},{emoji:"\uD83D\uDC79",title:"Ogre"},{emoji:"\uD83D\uDC7A",title:"Goblin"},{emoji:"\uD83D\uDC7B",title:"Ghost"},{emoji:"\uD83D\uDC7D",title:"Alien"},{emoji:"\uD83D\uDC7E",title:"Alien Monster"},{emoji:"\uD83E\uDD16",title:"Robot"},{emoji:"\uD83D\uDE3A",title:"Grinning Cat"},{emoji:"\uD83D\uDE38",title:"Grinning Cat with Smiling Eyes"},{emoji:"\uD83D\uDE39",title:"Cat with Tears of Joy"},{emoji:"\uD83D\uDE3B",title:"Smiling Cat with Heart-Eyes"},{emoji:"\uD83D\uDE3C",title:"Cat with Wry Smile"},{emoji:"\uD83D\uDE3D",title:"Kissing Cat"},{emoji:"\uD83D\uDE40",title:"Weary Cat"},{emoji:"\uD83D\uDE3F",title:"Crying Cat"},{emoji:"\uD83D\uDE3E",title:"Pouting Cat"},{emoji:"\uD83D\uDC8B",title:"Kiss Mark"},{emoji:"\uD83D\uDC4B",title:"Waving Hand"},{emoji:"\uD83E\uDD1A",title:"Raised Back of Hand"},{emoji:"\uD83D\uDD90️",title:"Hand with Fingers Splayed"},{emoji:"✋",title:"Raised Hand"},{emoji:"\uD83D\uDD96",title:"Vulcan Salute"},{emoji:"\uD83D\uDC4C",title:"OK Hand"},{emoji:"\uD83E\uDD0C",title:"Pinched Fingers"},{emoji:"\uD83E\uDD0F",title:"Pinching Hand"},{emoji:"✌️",title:"Victory Hand"},{emoji:"\uD83E\uDD1E",title:"Crossed Fingers"},{emoji:"\uD83E\uDD1F",title:"Love-You Gesture"},{emoji:"\uD83E\uDD18",title:"Sign of the Horns"},{emoji:"\uD83E\uDD19",title:"Call Me Hand"},{emoji:"\uD83D\uDC48",title:"Backhand Index Pointing Left"},{emoji:"\uD83D\uDC49",title:"Backhand Index Pointing Right"},{emoji:"\uD83D\uDC46",title:"Backhand Index Pointing Up"},{emoji:"\uD83D\uDD95",title:"Middle Finger"},{emoji:"\uD83D\uDC47",title:"Backhand Index Pointing Down"},{emoji:"☝️",title:"Index Pointing Up"},{emoji:"\uD83D\uDC4D",title:"Thumbs Up"},{emoji:"\uD83D\uDC4E",title:"Thumbs Down"},{emoji:"✊",title:"Raised Fist"},{emoji:"\uD83D\uDC4A",title:"Oncoming Fist"},{emoji:"\uD83E\uDD1B",title:"Left-Facing Fist"},{emoji:"\uD83E\uDD1C",title:"Right-Facing Fist"},{emoji:"\uD83D\uDC4F",title:"Clapping Hands"},{emoji:"\uD83D\uDE4C",title:"Raising Hands"},{emoji:"\uD83D\uDC50",title:"Open Hands"},{emoji:"\uD83E\uDD32",title:"Palms Up Together"},{emoji:"\uD83E\uDD1D",title:"Handshake"},{emoji:"\uD83D\uDE4F",title:"Folded Hands"},{emoji:"✍️",title:"Writing Hand"},{emoji:"\uD83D\uDC85",title:"Nail Polish"},{emoji:"\uD83E\uDD33",title:"Selfie"},{emoji:"\uD83D\uDCAA",title:"Flexed Biceps"},{emoji:"\uD83E\uDDBE",title:"Mechanical Arm"},{emoji:"\uD83E\uDDBF",title:"Mechanical Leg"},{emoji:"\uD83E\uDDB5",title:"Leg"},{emoji:"\uD83E\uDDB6",title:"Foot"},{emoji:"\uD83D\uDC42",title:"Ear"},{emoji:"\uD83E\uDDBB",title:"Ear with Hearing Aid"},{emoji:"\uD83D\uDC43",title:"Nose"},{emoji:"\uD83E\uDDE0",title:"Brain"},{emoji:"\uD83E\uDEC0",title:"Anatomical Heart"},{emoji:"\uD83E\uDEC1",title:"Lungs"},{emoji:"\uD83E\uDDB7",title:"Tooth"},{emoji:"\uD83E\uDDB4",title:"Bone"},{emoji:"\uD83D\uDC40",title:"Eyes"},{emoji:"\uD83D\uDC41️",title:"Eye"},{emoji:"\uD83D\uDC45",title:"Tongue"},{emoji:"\uD83D\uDC44",title:"Mouth"},{emoji:"\uD83D\uDC76",title:"Baby"},{emoji:"\uD83E\uDDD2",title:"Child"},{emoji:"\uD83D\uDC66",title:"Boy"},{emoji:"\uD83D\uDC67",title:"Girl"},{emoji:"\uD83E\uDDD1",title:"Person"},{emoji:"\uD83D\uDC71",title:"Person: Blond Hair"},{emoji:"\uD83D\uDC68",title:"Man"},{emoji:"\uD83E\uDDD4",title:"Person: Beard"},{emoji:"\uD83D\uDC68‍\uD83E\uDDB0",title:"Man: Red Hair"},{emoji:"\uD83D\uDC68‍\uD83E\uDDB1",title:"Man: Curly Hair"},{emoji:"\uD83D\uDC68‍\uD83E\uDDB3",title:"Man: White Hair"},{emoji:"\uD83D\uDC68‍\uD83E\uDDB2",title:"Man: Bald"},{emoji:"\uD83D\uDC69",title:"Woman"},{emoji:"\uD83D\uDC69‍\uD83E\uDDB0",title:"Woman: Red Hair"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDB0",title:"Person: Red Hair"},{emoji:"\uD83D\uDC69‍\uD83E\uDDB1",title:"Woman: Curly Hair"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDB1",title:"Person: Curly Hair"},{emoji:"\uD83D\uDC69‍\uD83E\uDDB3",title:"Woman: White Hair"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDB3",title:"Person: White Hair"},{emoji:"\uD83D\uDC69‍\uD83E\uDDB2",title:"Woman: Bald"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDB2",title:"Person: Bald"},{emoji:"\uD83D\uDC71‍♀️",title:"Woman: Blond Hair"},{emoji:"\uD83D\uDC71‍♂️",title:"Man: Blond Hair"},{emoji:"\uD83E\uDDD3",title:"Older Person"},{emoji:"\uD83D\uDC74",title:"Old Man"},{emoji:"\uD83D\uDC75",title:"Old Woman"},{emoji:"\uD83D\uDE4D",title:"Person Frowning"},{emoji:"\uD83D\uDE4D‍♂️",title:"Man Frowning"},{emoji:"\uD83D\uDE4D‍♀️",title:"Woman Frowning"},{emoji:"\uD83D\uDE4E",title:"Person Pouting"},{emoji:"\uD83D\uDE4E‍♂️",title:"Man Pouting"},{emoji:"\uD83D\uDE4E‍♀️",title:"Woman Pouting"},{emoji:"\uD83D\uDE45",title:"Person Gesturing No"},{emoji:"\uD83D\uDE45‍♂️",title:"Man Gesturing No"},{emoji:"\uD83D\uDE45‍♀️",title:"Woman Gesturing No"},{emoji:"\uD83D\uDE46",title:"Person Gesturing OK"},{emoji:"\uD83D\uDE46‍♂️",title:"Man Gesturing OK"},{emoji:"\uD83D\uDE46‍♀️",title:"Woman Gesturing OK"},{emoji:"\uD83D\uDC81",title:"Person Tipping Hand"},{emoji:"\uD83D\uDC81‍♂️",title:"Man Tipping Hand"},{emoji:"\uD83D\uDC81‍♀️",title:"Woman Tipping Hand"},{emoji:"\uD83D\uDE4B",title:"Person Raising Hand"},{emoji:"\uD83D\uDE4B‍♂️",title:"Man Raising Hand"},{emoji:"\uD83D\uDE4B‍♀️",title:"Woman Raising Hand"},{emoji:"\uD83E\uDDCF",title:"Deaf Person"},{emoji:"\uD83E\uDDCF‍♂️",title:"Deaf Man"},{emoji:"\uD83E\uDDCF‍♀️",title:"Deaf Woman"},{emoji:"\uD83D\uDE47",title:"Person Bowing"},{emoji:"\uD83D\uDE47‍♂️",title:"Man Bowing"},{emoji:"\uD83D\uDE47‍♀️",title:"Woman Bowing"},{emoji:"\uD83E\uDD26",title:"Person Facepalming"},{emoji:"\uD83E\uDD26‍♂️",title:"Man Facepalming"},{emoji:"\uD83E\uDD26‍♀️",title:"Woman Facepalming"},{emoji:"\uD83E\uDD37",title:"Person Shrugging"},{emoji:"\uD83E\uDD37‍♂️",title:"Man Shrugging"},{emoji:"\uD83E\uDD37‍♀️",title:"Woman Shrugging"},{emoji:"\uD83E\uDDD1‍⚕️",title:"Health Worker"},{emoji:"\uD83D\uDC68‍⚕️",title:"Man Health Worker"},{emoji:"\uD83D\uDC69‍⚕️",title:"Woman Health Worker"},{emoji:"\uD83E\uDDD1‍\uD83C\uDF93",title:"Student"},{emoji:"\uD83D\uDC68‍\uD83C\uDF93",title:"Man Student"},{emoji:"\uD83D\uDC69‍\uD83C\uDF93",title:"Woman Student"},{emoji:"\uD83E\uDDD1‍\uD83C\uDFEB",title:"Teacher"},{emoji:"\uD83D\uDC68‍\uD83C\uDFEB",title:"Man Teacher"},{emoji:"\uD83D\uDC69‍\uD83C\uDFEB",title:"Woman Teacher"},{emoji:"\uD83E\uDDD1‍⚖️",title:"Judge"},{emoji:"\uD83D\uDC68‍⚖️",title:"Man Judge"},{emoji:"\uD83D\uDC69‍⚖️",title:"Woman Judge"},{emoji:"\uD83E\uDDD1‍\uD83C\uDF3E",title:"Farmer"},{emoji:"\uD83D\uDC68‍\uD83C\uDF3E",title:"Man Farmer"},{emoji:"\uD83D\uDC69‍\uD83C\uDF3E",title:"Woman Farmer"},{emoji:"\uD83E\uDDD1‍\uD83C\uDF73",title:"Cook"},{emoji:"\uD83D\uDC68‍\uD83C\uDF73",title:"Man Cook"},{emoji:"\uD83D\uDC69‍\uD83C\uDF73",title:"Woman Cook"},{emoji:"\uD83E\uDDD1‍\uD83D\uDD27",title:"Mechanic"},{emoji:"\uD83D\uDC68‍\uD83D\uDD27",title:"Man Mechanic"},{emoji:"\uD83D\uDC69‍\uD83D\uDD27",title:"Woman Mechanic"},{emoji:"\uD83E\uDDD1‍\uD83C\uDFED",title:"Factory Worker"},{emoji:"\uD83D\uDC68‍\uD83C\uDFED",title:"Man Factory Worker"},{emoji:"\uD83D\uDC69‍\uD83C\uDFED",title:"Woman Factory Worker"},{emoji:"\uD83E\uDDD1‍\uD83D\uDCBC",title:"Office Worker"},{emoji:"\uD83D\uDC68‍\uD83D\uDCBC",title:"Man Office Worker"},{emoji:"\uD83D\uDC69‍\uD83D\uDCBC",title:"Woman Office Worker"},{emoji:"\uD83E\uDDD1‍\uD83D\uDD2C",title:"Scientist"},{emoji:"\uD83D\uDC68‍\uD83D\uDD2C",title:"Man Scientist"},{emoji:"\uD83D\uDC69‍\uD83D\uDD2C",title:"Woman Scientist"},{emoji:"\uD83E\uDDD1‍\uD83D\uDCBB",title:"Technologist"},{emoji:"\uD83D\uDC68‍\uD83D\uDCBB",title:"Man Technologist"},{emoji:"\uD83D\uDC69‍\uD83D\uDCBB",title:"Woman Technologist"},{emoji:"\uD83E\uDDD1‍\uD83C\uDFA4",title:"Singer"},{emoji:"\uD83D\uDC68‍\uD83C\uDFA4",title:"Man Singer"},{emoji:"\uD83D\uDC69‍\uD83C\uDFA4",title:"Woman Singer"},{emoji:"\uD83E\uDDD1‍\uD83C\uDFA8",title:"Artist"},{emoji:"\uD83D\uDC68‍\uD83C\uDFA8",title:"Man Artist"},{emoji:"\uD83D\uDC69‍\uD83C\uDFA8",title:"Woman Artist"},{emoji:"\uD83E\uDDD1‍✈️",title:"Pilot"},{emoji:"\uD83D\uDC68‍✈️",title:"Man Pilot"},{emoji:"\uD83D\uDC69‍✈️",title:"Woman Pilot"},{emoji:"\uD83E\uDDD1‍\uD83D\uDE80",title:"Astronaut"},{emoji:"\uD83D\uDC68‍\uD83D\uDE80",title:"Man Astronaut"},{emoji:"\uD83D\uDC69‍\uD83D\uDE80",title:"Woman Astronaut"},{emoji:"\uD83E\uDDD1‍\uD83D\uDE92",title:"Firefighter"},{emoji:"\uD83D\uDC68‍\uD83D\uDE92",title:"Man Firefighter"},{emoji:"\uD83D\uDC69‍\uD83D\uDE92",title:"Woman Firefighter"},{emoji:"\uD83D\uDC6E",title:"Police Officer"},{emoji:"\uD83D\uDC6E‍♂️",title:"Man Police Officer"},{emoji:"\uD83D\uDC6E‍♀️",title:"Woman Police Officer"},{emoji:"\uD83D\uDD75️",title:"Detective"},{emoji:"\uD83D\uDD75️‍♂️",title:"Man Detective"},{emoji:"\uD83D\uDD75️‍♀️",title:"Woman Detective"},{emoji:"\uD83D\uDC82",title:"Guard"},{emoji:"\uD83D\uDC82‍♂️",title:"Man Guard"},{emoji:"\uD83D\uDC82‍♀️",title:"Woman Guard"},{emoji:"\uD83E\uDD77",title:"Ninja"},{emoji:"\uD83D\uDC77",title:"Construction Worker"},{emoji:"\uD83D\uDC77‍♂️",title:"Man Construction Worker"},{emoji:"\uD83D\uDC77‍♀️",title:"Woman Construction Worker"},{emoji:"\uD83E\uDD34",title:"Prince"},{emoji:"\uD83D\uDC78",title:"Princess"},{emoji:"\uD83D\uDC73",title:"Person Wearing Turban"},{emoji:"\uD83D\uDC73‍♂️",title:"Man Wearing Turban"},{emoji:"\uD83D\uDC73‍♀️",title:"Woman Wearing Turban"},{emoji:"\uD83D\uDC72",title:"Person with Skullcap"},{emoji:"\uD83E\uDDD5",title:"Woman with Headscarf"},{emoji:"\uD83E\uDD35",title:"Person in Tuxedo"},{emoji:"\uD83E\uDD35‍♂️",title:"Man in Tuxedo"},{emoji:"\uD83E\uDD35‍♀️",title:"Woman in Tuxedo"},{emoji:"\uD83D\uDC70",title:"Person with Veil"},{emoji:"\uD83D\uDC70‍♂️",title:"Man with Veil"},{emoji:"\uD83D\uDC70‍♀️",title:"Woman with Veil"},{emoji:"\uD83E\uDD30",title:"Pregnant Woman"},{emoji:"\uD83E\uDD31",title:"Breast-Feeding"},{emoji:"\uD83D\uDC69‍\uD83C\uDF7C",title:"Woman Feeding Baby"},{emoji:"\uD83D\uDC68‍\uD83C\uDF7C",title:"Man Feeding Baby"},{emoji:"\uD83E\uDDD1‍\uD83C\uDF7C",title:"Person Feeding Baby"},{emoji:"\uD83D\uDC7C",title:"Baby Angel"},{emoji:"\uD83C\uDF85",title:"Santa Claus"},{emoji:"\uD83E\uDD36",title:"Mrs. Claus"},{emoji:"\uD83E\uDDD1‍\uD83C\uDF84",title:"Mx Claus"},{emoji:"\uD83E\uDDB8",title:"Superhero"},{emoji:"\uD83E\uDDB8‍♂️",title:"Man Superhero"},{emoji:"\uD83E\uDDB8‍♀️",title:"Woman Superhero"},{emoji:"\uD83E\uDDB9",title:"Supervillain"},{emoji:"\uD83E\uDDB9‍♂️",title:"Man Supervillain"},{emoji:"\uD83E\uDDB9‍♀️",title:"Woman Supervillain"},{emoji:"\uD83E\uDDD9",title:"Mage"},{emoji:"\uD83E\uDDD9‍♂️",title:"Man Mage"},{emoji:"\uD83E\uDDD9‍♀️",title:"Woman Mage"},{emoji:"\uD83E\uDDDA",title:"Fairy"},{emoji:"\uD83E\uDDDA‍♂️",title:"Man Fairy"},{emoji:"\uD83E\uDDDA‍♀️",title:"Woman Fairy"},{emoji:"\uD83E\uDDDB",title:"Vampire"},{emoji:"\uD83E\uDDDB‍♂️",title:"Man Vampire"},{emoji:"\uD83E\uDDDB‍♀️",title:"Woman Vampire"},{emoji:"\uD83E\uDDDC",title:"Merperson"},{emoji:"\uD83E\uDDDC‍♂️",title:"Merman"},{emoji:"\uD83E\uDDDC‍♀️",title:"Mermaid"},{emoji:"\uD83E\uDDDD",title:"Elf"},{emoji:"\uD83E\uDDDD‍♂️",title:"Man Elf"},{emoji:"\uD83E\uDDDD‍♀️",title:"Woman Elf"},{emoji:"\uD83E\uDDDE",title:"Genie"},{emoji:"\uD83E\uDDDE‍♂️",title:"Man Genie"},{emoji:"\uD83E\uDDDE‍♀️",title:"Woman Genie"},{emoji:"\uD83E\uDDDF",title:"Zombie"},{emoji:"\uD83E\uDDDF‍♂️",title:"Man Zombie"},{emoji:"\uD83E\uDDDF‍♀️",title:"Woman Zombie"},{emoji:"\uD83D\uDC86",title:"Person Getting Massage"},{emoji:"\uD83D\uDC86‍♂️",title:"Man Getting Massage"},{emoji:"\uD83D\uDC86‍♀️",title:"Woman Getting Massage"},{emoji:"\uD83D\uDC87",title:"Person Getting Haircut"},{emoji:"\uD83D\uDC87‍♂️",title:"Man Getting Haircut"},{emoji:"\uD83D\uDC87‍♀️",title:"Woman Getting Haircut"},{emoji:"\uD83D\uDEB6",title:"Person Walking"},{emoji:"\uD83D\uDEB6‍♂️",title:"Man Walking"},{emoji:"\uD83D\uDEB6‍♀️",title:"Woman Walking"},{emoji:"\uD83E\uDDCD",title:"Person Standing"},{emoji:"\uD83E\uDDCD‍♂️",title:"Man Standing"},{emoji:"\uD83E\uDDCD‍♀️",title:"Woman Standing"},{emoji:"\uD83E\uDDCE",title:"Person Kneeling"},{emoji:"\uD83E\uDDCE‍♂️",title:"Man Kneeling"},{emoji:"\uD83E\uDDCE‍♀️",title:"Woman Kneeling"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDAF",title:"Person with White Cane"},{emoji:"\uD83D\uDC68‍\uD83E\uDDAF",title:"Man with White Cane"},{emoji:"\uD83D\uDC69‍\uD83E\uDDAF",title:"Woman with White Cane"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDBC",title:"Person in Motorized Wheelchair"},{emoji:"\uD83D\uDC68‍\uD83E\uDDBC",title:"Man in Motorized Wheelchair"},{emoji:"\uD83D\uDC69‍\uD83E\uDDBC",title:"Woman in Motorized Wheelchair"},{emoji:"\uD83E\uDDD1‍\uD83E\uDDBD",title:"Person in Manual Wheelchair"},{emoji:"\uD83D\uDC68‍\uD83E\uDDBD",title:"Man in Manual Wheelchair"},{emoji:"\uD83D\uDC69‍\uD83E\uDDBD",title:"Woman in Manual Wheelchair"},{emoji:"\uD83C\uDFC3",title:"Person Running"},{emoji:"\uD83C\uDFC3‍♂️",title:"Man Running"},{emoji:"\uD83C\uDFC3‍♀️",title:"Woman Running"},{emoji:"\uD83D\uDC83",title:"Woman Dancing"},{emoji:"\uD83D\uDD7A",title:"Man Dancing"},{emoji:"\uD83D\uDD74️",title:"Person in Suit Levitating"},{emoji:"\uD83D\uDC6F",title:"People with Bunny Ears"},{emoji:"\uD83D\uDC6F‍♂️",title:"Men with Bunny Ears"},{emoji:"\uD83D\uDC6F‍♀️",title:"Women with Bunny Ears"},{emoji:"\uD83E\uDDD6",title:"Person in Steamy Room"},{emoji:"\uD83E\uDDD6‍♂️",title:"Man in Steamy Room"},{emoji:"\uD83E\uDDD6‍♀️",title:"Woman in Steamy Room"},{emoji:"\uD83E\uDDD8",title:"Person in Lotus Position"},{emoji:"\uD83E\uDDD1‍\uD83E\uDD1D‍\uD83E\uDDD1",title:"People Holding Hands"},{emoji:"\uD83D\uDC6D",title:"Women Holding Hands"},{emoji:"\uD83D\uDC6B",title:"Woman and Man Holding Hands"},{emoji:"\uD83D\uDC6C",title:"Men Holding Hands"},{emoji:"\uD83D\uDC8F",title:"Kiss"},{emoji:"\uD83D\uDC69‍❤️‍\uD83D\uDC8B‍\uD83D\uDC68",title:"Kiss: Woman, Man"},{emoji:"\uD83D\uDC68‍❤️‍\uD83D\uDC8B‍\uD83D\uDC68",title:"Kiss: Man, Man"},{emoji:"\uD83D\uDC69‍❤️‍\uD83D\uDC8B‍\uD83D\uDC69",title:"Kiss: Woman, Woman"},{emoji:"\uD83D\uDC91",title:"Couple with Heart"},{emoji:"\uD83D\uDC69‍❤️‍\uD83D\uDC68",title:"Couple with Heart: Woman, Man"},{emoji:"\uD83D\uDC68‍❤️‍\uD83D\uDC68",title:"Couple with Heart: Man, Man"},{emoji:"\uD83D\uDC69‍❤️‍\uD83D\uDC69",title:"Couple with Heart: Woman, Woman"},{emoji:"\uD83D\uDC6A",title:"Family"},{emoji:"\uD83D\uDC68‍\uD83D\uDC69‍\uD83D\uDC66",title:"Family: Man, Woman, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC69‍\uD83D\uDC67",title:"Family: Man, Woman, Girl"},{emoji:"\uD83D\uDC68‍\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC66",title:"Family: Man, Woman, Girl, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC69‍\uD83D\uDC66‍\uD83D\uDC66",title:"Family: Man, Woman, Boy, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC67",title:"Family: Man, Woman, Girl, Girl"},{emoji:"\uD83D\uDC68‍\uD83D\uDC68‍\uD83D\uDC66",title:"Family: Man, Man, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC68‍\uD83D\uDC67",title:"Family: Man, Man, Girl"},{emoji:"\uD83D\uDC68‍\uD83D\uDC68‍\uD83D\uDC67‍\uD83D\uDC66",title:"Family: Man, Man, Girl, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC68‍\uD83D\uDC66‍\uD83D\uDC66",title:"Family: Man, Man, Boy, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC68‍\uD83D\uDC67‍\uD83D\uDC67",title:"Family: Man, Man, Girl, Girl"},{emoji:"\uD83D\uDC69‍\uD83D\uDC69‍\uD83D\uDC66",title:"Family: Woman, Woman, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC69‍\uD83D\uDC67",title:"Family: Woman, Woman, Girl"},{emoji:"\uD83D\uDC69‍\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC66",title:"Family: Woman, Woman, Girl, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC69‍\uD83D\uDC66‍\uD83D\uDC66",title:"Family: Woman, Woman, Boy, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC67",title:"Family: Woman, Woman, Girl, Girl"},{emoji:"\uD83D\uDC68‍\uD83D\uDC66",title:"Family: Man, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC66‍\uD83D\uDC66",title:"Family: Man, Boy, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC67",title:"Family: Man, Girl"},{emoji:"\uD83D\uDC68‍\uD83D\uDC67‍\uD83D\uDC66",title:"Family: Man, Girl, Boy"},{emoji:"\uD83D\uDC68‍\uD83D\uDC67‍\uD83D\uDC67",title:"Family: Man, Girl, Girl"},{emoji:"\uD83D\uDC69‍\uD83D\uDC66",title:"Family: Woman, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC66‍\uD83D\uDC66",title:"Family: Woman, Boy, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC67",title:"Family: Woman, Girl"},{emoji:"\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC66",title:"Family: Woman, Girl, Boy"},{emoji:"\uD83D\uDC69‍\uD83D\uDC67‍\uD83D\uDC67",title:"Family: Woman, Girl, Girl"},{emoji:"\uD83D\uDDE3️",title:"Speaking Head"},{emoji:"\uD83D\uDC64",title:"Bust in Silhouette"},{emoji:"\uD83D\uDC65",title:"Busts in Silhouette"},{emoji:"\uD83E\uDEC2",title:"People Hugging"},{emoji:"\uD83D\uDC63",title:"Footprints"},{emoji:"\uD83E\uDDF3",title:"Luggage"},{emoji:"\uD83C\uDF02",title:"Closed Umbrella"},{emoji:"☂️",title:"Umbrella"},{emoji:"\uD83C\uDF83",title:"Jack-O-Lantern"},{emoji:"\uD83E\uDDF5",title:"Thread"},{emoji:"\uD83E\uDDF6",title:"Yarn"},{emoji:"\uD83D\uDC53",title:"Glasses"},{emoji:"\uD83D\uDD76️",title:"Sunglasses"},{emoji:"\uD83E\uDD7D",title:"Goggles"},{emoji:"\uD83E\uDD7C",title:"Lab Coat"},{emoji:"\uD83E\uDDBA",title:"Safety Vest"},{emoji:"\uD83D\uDC54",title:"Necktie"},{emoji:"\uD83D\uDC55",title:"T-Shirt"},{emoji:"\uD83D\uDC56",title:"Jeans"},{emoji:"\uD83E\uDDE3",title:"Scarf"},{emoji:"\uD83E\uDDE4",title:"Gloves"},{emoji:"\uD83E\uDDE5",title:"Coat"},{emoji:"\uD83E\uDDE6",title:"Socks"},{emoji:"\uD83D\uDC57",title:"Dress"},{emoji:"\uD83D\uDC58",title:"Kimono"},{emoji:"\uD83E\uDD7B",title:"Sari"},{emoji:"\uD83E\uDE71",title:"One-Piece Swimsuit"},{emoji:"\uD83E\uDE72",title:"Briefs"},{emoji:"\uD83E\uDE73",title:"Shorts"},{emoji:"\uD83D\uDC59",title:"Bikini"},{emoji:"\uD83D\uDC5A",title:"Woman’s Clothes"},{emoji:"\uD83D\uDC5B",title:"Purse"},{emoji:"\uD83D\uDC5C",title:"Handbag"},{emoji:"\uD83D\uDC5D",title:"Clutch Bag"},{emoji:"\uD83C\uDF92",title:"Backpack"},{emoji:"\uD83E\uDE74",title:"Thong Sandal"},{emoji:"\uD83D\uDC5E",title:"Man’s Shoe"},{emoji:"\uD83D\uDC5F",title:"Running Shoe"},{emoji:"\uD83E\uDD7E",title:"Hiking Boot"},{emoji:"\uD83E\uDD7F",title:"Flat Shoe"},{emoji:"\uD83D\uDC60",title:"High-Heeled Shoe"},{emoji:"\uD83D\uDC61",title:"Woman’s Sandal"},{emoji:"\uD83E\uDE70",title:"Ballet Shoes"},{emoji:"\uD83D\uDC62",title:"Woman’s Boot"},{emoji:"\uD83D\uDC51",title:"Crown"},{emoji:"\uD83D\uDC52",title:"Woman’s Hat"},{emoji:"\uD83C\uDFA9",title:"Top Hat"},{emoji:"\uD83C\uDF93",title:"Graduation Cap"},{emoji:"\uD83E\uDDE2",title:"Billed Cap"},{emoji:"\uD83E\uDE96",title:"Military Helmet"},{emoji:"⛑️",title:"Rescue Worker’s Helmet"},{emoji:"\uD83D\uDC84",title:"Lipstick"},{emoji:"\uD83D\uDC8D",title:"Ring"},{emoji:"\uD83D\uDCBC",title:"Briefcase"},{emoji:"\uD83E\uDE78",title:"Drop of Blood"}],Nature:[{emoji:"\uD83D\uDE48",title:"See-No-Evil Monkey"},{emoji:"\uD83D\uDE49",title:"Hear-No-Evil Monkey"},{emoji:"\uD83D\uDE4A",title:"Speak-No-Evil Monkey"},{emoji:"\uD83D\uDCA5",title:"Collision"},{emoji:"\uD83D\uDCAB",title:"Dizzy"},{emoji:"\uD83D\uDCA6",title:"Sweat Droplets"},{emoji:"\uD83D\uDCA8",title:"Dashing Away"},{emoji:"\uD83D\uDC35",title:"Monkey Face"},{emoji:"\uD83D\uDC12",title:"Monkey"},{emoji:"\uD83E\uDD8D",title:"Gorilla"},{emoji:"\uD83E\uDDA7",title:"Orangutan"},{emoji:"\uD83D\uDC36",title:"Dog Face"},{emoji:"\uD83D\uDC15",title:"Dog"},{emoji:"\uD83E\uDDAE",title:"Guide Dog"},{emoji:"\uD83D\uDC15‍\uD83E\uDDBA",title:"Service Dog"},{emoji:"\uD83D\uDC29",title:"Poodle"},{emoji:"\uD83D\uDC3A",title:"Wolf"},{emoji:"\uD83E\uDD8A",title:"Fox"},{emoji:"\uD83E\uDD9D",title:"Raccoon"},{emoji:"\uD83D\uDC31",title:"Cat Face"},{emoji:"\uD83D\uDC08",title:"Cat"},{emoji:"\uD83D\uDC08‍⬛",title:"Black Cat"},{emoji:"\uD83E\uDD81",title:"Lion"},{emoji:"\uD83D\uDC2F",title:"Tiger Face"},{emoji:"\uD83D\uDC05",title:"Tiger"},{emoji:"\uD83D\uDC06",title:"Leopard"},{emoji:"\uD83D\uDC34",title:"Horse Face"},{emoji:"\uD83D\uDC0E",title:"Horse"},{emoji:"\uD83E\uDD84",title:"Unicorn"},{emoji:"\uD83E\uDD93",title:"Zebra"},{emoji:"\uD83E\uDD8C",title:"Deer"},{emoji:"\uD83E\uDDAC",title:"Bison"},{emoji:"\uD83D\uDC2E",title:"Cow Face"},{emoji:"\uD83D\uDC02",title:"Ox"},{emoji:"\uD83D\uDC03",title:"Water Buffalo"},{emoji:"\uD83D\uDC04",title:"Cow"},{emoji:"\uD83D\uDC37",title:"Pig Face"},{emoji:"\uD83D\uDC16",title:"Pig"},{emoji:"\uD83D\uDC17",title:"Boar"},{emoji:"\uD83D\uDC3D",title:"Pig Nose"},{emoji:"\uD83D\uDC0F",title:"Ram"},{emoji:"\uD83D\uDC11",title:"Ewe"},{emoji:"\uD83D\uDC10",title:"Goat"},{emoji:"\uD83D\uDC2A",title:"Camel"},{emoji:"\uD83D\uDC2B",title:"Two-Hump Camel"},{emoji:"\uD83E\uDD99",title:"Llama"},{emoji:"\uD83E\uDD92",title:"Giraffe"},{emoji:"\uD83D\uDC18",title:"Elephant"},{emoji:"\uD83E\uDDA3",title:"Mammoth"},{emoji:"\uD83E\uDD8F",title:"Rhinoceros"},{emoji:"\uD83E\uDD9B",title:"Hippopotamus"},{emoji:"\uD83D\uDC2D",title:"Mouse Face"},{emoji:"\uD83D\uDC01",title:"Mouse"},{emoji:"\uD83D\uDC00",title:"Rat"},{emoji:"\uD83D\uDC39",title:"Hamster"},{emoji:"\uD83D\uDC30",title:"Rabbit Face"},{emoji:"\uD83D\uDC07",title:"Rabbit"},{emoji:"\uD83D\uDC3F️",title:"Chipmunk"},{emoji:"\uD83E\uDDAB",title:"Beaver"},{emoji:"\uD83E\uDD94",title:"Hedgehog"},{emoji:"\uD83E\uDD87",title:"Bat"},{emoji:"\uD83D\uDC3B",title:"Bear"},{emoji:"\uD83D\uDC3B‍❄️",title:"Polar Bear"},{emoji:"\uD83D\uDC28",title:"Koala"},{emoji:"\uD83D\uDC3C",title:"Panda"},{emoji:"\uD83E\uDDA5",title:"Sloth"},{emoji:"\uD83E\uDDA6",title:"Otter"},{emoji:"\uD83E\uDDA8",title:"Skunk"},{emoji:"\uD83E\uDD98",title:"Kangaroo"},{emoji:"\uD83E\uDDA1",title:"Badger"},{emoji:"\uD83D\uDC3E",title:"Paw Prints"},{emoji:"\uD83E\uDD83",title:"Turkey"},{emoji:"\uD83D\uDC14",title:"Chicken"},{emoji:"\uD83D\uDC13",title:"Rooster"},{emoji:"\uD83D\uDC23",title:"Hatching Chick"},{emoji:"\uD83D\uDC24",title:"Baby Chick"},{emoji:"\uD83D\uDC25",title:"Front-Facing Baby Chick"},{emoji:"\uD83D\uDC26",title:"Bird"},{emoji:"\uD83D\uDC27",title:"Penguin"},{emoji:"\uD83D\uDD4A️",title:"Dove"},{emoji:"\uD83E\uDD85",title:"Eagle"},{emoji:"\uD83E\uDD86",title:"Duck"},{emoji:"\uD83E\uDDA2",title:"Swan"},{emoji:"\uD83E\uDD89",title:"Owl"},{emoji:"\uD83E\uDDA4",title:"Dodo"},{emoji:"\uD83E\uDEB6",title:"Feather"},{emoji:"\uD83E\uDDA9",title:"Flamingo"},{emoji:"\uD83E\uDD9A",title:"Peacock"},{emoji:"\uD83E\uDD9C",title:"Parrot"},{emoji:"\uD83D\uDC38",title:"Frog"},{emoji:"\uD83D\uDC0A",title:"Crocodile"},{emoji:"\uD83D\uDC22",title:"Turtle"},{emoji:"\uD83E\uDD8E",title:"Lizard"},{emoji:"\uD83D\uDC0D",title:"Snake"},{emoji:"\uD83D\uDC32",title:"Dragon Face"},{emoji:"\uD83D\uDC09",title:"Dragon"},{emoji:"\uD83E\uDD95",title:"Sauropod"},{emoji:"\uD83E\uDD96",title:"T-Rex"},{emoji:"\uD83D\uDC33",title:"Spouting Whale"},{emoji:"\uD83D\uDC0B",title:"Whale"},{emoji:"\uD83D\uDC2C",title:"Dolphin"},{emoji:"\uD83E\uDDAD",title:"Seal"},{emoji:"\uD83D\uDC1F",title:"Fish"},{emoji:"\uD83D\uDC20",title:"Tropical Fish"},{emoji:"\uD83D\uDC21",title:"Blowfish"},{emoji:"\uD83E\uDD88",title:"Shark"},{emoji:"\uD83D\uDC19",title:"Octopus"},{emoji:"\uD83D\uDC1A",title:"Spiral Shell"},{emoji:"\uD83D\uDC0C",title:"Snail"},{emoji:"\uD83E\uDD8B",title:"Butterfly"},{emoji:"\uD83D\uDC1B",title:"Bug"},{emoji:"\uD83D\uDC1C",title:"Ant"},{emoji:"\uD83D\uDC1D",title:"Honeybee"},{emoji:"\uD83E\uDEB2",title:"Beetle"},{emoji:"\uD83D\uDC1E",title:"Lady Beetle"},{emoji:"\uD83E\uDD97",title:"Cricket"},{emoji:"\uD83E\uDEB3",title:"Cockroach"},{emoji:"\uD83D\uDD77️",title:"Spider"},{emoji:"\uD83D\uDD78️",title:"Spider Web"},{emoji:"\uD83E\uDD82",title:"Scorpion"},{emoji:"\uD83E\uDD9F",title:"Mosquito"},{emoji:"\uD83E\uDEB0",title:"Fly"},{emoji:"\uD83E\uDEB1",title:"Worm"},{emoji:"\uD83E\uDDA0",title:"Microbe"},{emoji:"\uD83D\uDC90",title:"Bouquet"},{emoji:"\uD83C\uDF38",title:"Cherry Blossom"},{emoji:"\uD83D\uDCAE",title:"White Flower"},{emoji:"\uD83C\uDFF5️",title:"Rosette"},{emoji:"\uD83C\uDF39",title:"Rose"},{emoji:"\uD83E\uDD40",title:"Wilted Flower"},{emoji:"\uD83C\uDF3A",title:"Hibiscus"},{emoji:"\uD83C\uDF3B",title:"Sunflower"},{emoji:"\uD83C\uDF3C",title:"Blossom"},{emoji:"\uD83C\uDF37",title:"Tulip"},{emoji:"\uD83C\uDF31",title:"Seedling"},{emoji:"\uD83E\uDEB4",title:"Potted Plant"},{emoji:"\uD83C\uDF32",title:"Evergreen Tree"},{emoji:"\uD83C\uDF33",title:"Deciduous Tree"},{emoji:"\uD83C\uDF34",title:"Palm Tree"},{emoji:"\uD83C\uDF35",title:"Cactus"},{emoji:"\uD83C\uDF3E",title:"Sheaf of Rice"},{emoji:"\uD83C\uDF3F",title:"Herb"},{emoji:"☘️",title:"Shamrock"},{emoji:"\uD83C\uDF40",title:"Four Leaf Clover"},{emoji:"\uD83C\uDF41",title:"Maple Leaf"},{emoji:"\uD83C\uDF42",title:"Fallen Leaf"},{emoji:"\uD83C\uDF43",title:"Leaf Fluttering in Wind"},{emoji:"\uD83C\uDF44",title:"Mushroom"},{emoji:"\uD83C\uDF30",title:"Chestnut"},{emoji:"\uD83E\uDD80",title:"Crab"},{emoji:"\uD83E\uDD9E",title:"Lobster"},{emoji:"\uD83E\uDD90",title:"Shrimp"},{emoji:"\uD83E\uDD91",title:"Squid"},{emoji:"\uD83C\uDF0D",title:"Globe Showing Europe-Africa"},{emoji:"\uD83C\uDF0E",title:"Globe Showing Americas"},{emoji:"\uD83C\uDF0F",title:"Globe Showing Asia-Australia"},{emoji:"\uD83C\uDF10",title:"Globe with Meridians"},{emoji:"\uD83E\uDEA8",title:"Rock"},{emoji:"\uD83C\uDF11",title:"New Moon"},{emoji:"\uD83C\uDF12",title:"Waxing Crescent Moon"},{emoji:"\uD83C\uDF13",title:"First Quarter Moon"},{emoji:"\uD83C\uDF14",title:"Waxing Gibbous Moon"},{emoji:"\uD83C\uDF15",title:"Full Moon"},{emoji:"\uD83C\uDF16",title:"Waning Gibbous Moon"},{emoji:"\uD83C\uDF17",title:"Last Quarter Moon"},{emoji:"\uD83C\uDF18",title:"Waning Crescent Moon"},{emoji:"\uD83C\uDF19",title:"Crescent Moon"},{emoji:"\uD83C\uDF1A",title:"New Moon Face"},{emoji:"\uD83C\uDF1B",title:"First Quarter Moon Face"},{emoji:"\uD83C\uDF1C",title:"Last Quarter Moon Face"},{emoji:"☀️",title:"Sun"},{emoji:"\uD83C\uDF1D",title:"Full Moon Face"},{emoji:"\uD83C\uDF1E",title:"Sun with Face"},{emoji:"⭐",title:"Star"},{emoji:"\uD83C\uDF1F",title:"Glowing Star"},{emoji:"\uD83C\uDF20",title:"Shooting Star"},{emoji:"☁️",title:"Cloud"},{emoji:"⛅",title:"Sun Behind Cloud"},{emoji:"⛈️",title:"Cloud with Lightning and Rain"},{emoji:"\uD83C\uDF24️",title:"Sun Behind Small Cloud"},{emoji:"\uD83C\uDF25️",title:"Sun Behind Large Cloud"},{emoji:"\uD83C\uDF26️",title:"Sun Behind Rain Cloud"},{emoji:"\uD83C\uDF27️",title:"Cloud with Rain"},{emoji:"\uD83C\uDF28️",title:"Cloud with Snow"},{emoji:"\uD83C\uDF29️",title:"Cloud with Lightning"},{emoji:"\uD83C\uDF2A️",title:"Tornado"},{emoji:"\uD83C\uDF2B️",title:"Fog"},{emoji:"\uD83C\uDF2C️",title:"Wind Face"},{emoji:"\uD83C\uDF08",title:"Rainbow"},{emoji:"☂️",title:"Umbrella"},{emoji:"☔",title:"Umbrella with Rain Drops"},{emoji:"⚡",title:"High Voltage"},{emoji:"❄️",title:"Snowflake"},{emoji:"☃️",title:"Snowman"},{emoji:"⛄",title:"Snowman Without Snow"},{emoji:"☄️",title:"Comet"},{emoji:"\uD83D\uDD25",title:"Fire"},{emoji:"\uD83D\uDCA7",title:"Droplet"},{emoji:"\uD83C\uDF0A",title:"Water Wave"},{emoji:"\uD83C\uDF84",title:"Christmas Tree"},{emoji:"✨",title:"Sparkles"},{emoji:"\uD83C\uDF8B",title:"Tanabata Tree"},{emoji:"\uD83C\uDF8D",title:"Pine Decoration"}],"Food-dring":[{emoji:"\uD83C\uDF47",title:"Grapes"},{emoji:"\uD83C\uDF48",title:"Melon"},{emoji:"\uD83C\uDF49",title:"Watermelon"},{emoji:"\uD83C\uDF4A",title:"Tangerine"},{emoji:"\uD83C\uDF4B",title:"Lemon"},{emoji:"\uD83C\uDF4C",title:"Banana"},{emoji:"\uD83C\uDF4D",title:"Pineapple"},{emoji:"\uD83E\uDD6D",title:"Mango"},{emoji:"\uD83C\uDF4E",title:"Red Apple"},{emoji:"\uD83C\uDF4F",title:"Green Apple"},{emoji:"\uD83C\uDF50",title:"Pear"},{emoji:"\uD83C\uDF51",title:"Peach"},{emoji:"\uD83C\uDF52",title:"Cherries"},{emoji:"\uD83C\uDF53",title:"Strawberry"},{emoji:"\uD83E\uDED0",title:"Blueberries"},{emoji:"\uD83E\uDD5D",title:"Kiwi Fruit"},{emoji:"\uD83C\uDF45",title:"Tomato"},{emoji:"\uD83E\uDED2",title:"Olive"},{emoji:"\uD83E\uDD65",title:"Coconut"},{emoji:"\uD83E\uDD51",title:"Avocado"},{emoji:"\uD83C\uDF46",title:"Eggplant"},{emoji:"\uD83E\uDD54",title:"Potato"},{emoji:"\uD83E\uDD55",title:"Carrot"},{emoji:"\uD83C\uDF3D",title:"Ear of Corn"},{emoji:"\uD83C\uDF36️",title:"Hot Pepper"},{emoji:"\uD83E\uDED1",title:"Bell Pepper"},{emoji:"\uD83E\uDD52",title:"Cucumber"},{emoji:"\uD83E\uDD6C",title:"Leafy Green"},{emoji:"\uD83E\uDD66",title:"Broccoli"},{emoji:"\uD83E\uDDC4",title:"Garlic"},{emoji:"\uD83E\uDDC5",title:"Onion"},{emoji:"\uD83C\uDF44",title:"Mushroom"},{emoji:"\uD83E\uDD5C",title:"Peanuts"},{emoji:"\uD83C\uDF30",title:"Chestnut"},{emoji:"\uD83C\uDF5E",title:"Bread"},{emoji:"\uD83E\uDD50",title:"Croissant"},{emoji:"\uD83E\uDD56",title:"Baguette Bread"},{emoji:"\uD83E\uDED3",title:"Flatbread"},{emoji:"\uD83E\uDD68",title:"Pretzel"},{emoji:"\uD83E\uDD6F",title:"Bagel"},{emoji:"\uD83E\uDD5E",title:"Pancakes"},{emoji:"\uD83E\uDDC7",title:"Waffle"},{emoji:"\uD83E\uDDC0",title:"Cheese Wedge"},{emoji:"\uD83C\uDF56",title:"Meat on Bone"},{emoji:"\uD83C\uDF57",title:"Poultry Leg"},{emoji:"\uD83E\uDD69",title:"Cut of Meat"},{emoji:"\uD83E\uDD53",title:"Bacon"},{emoji:"\uD83C\uDF54",title:"Hamburger"},{emoji:"\uD83C\uDF5F",title:"French Fries"},{emoji:"\uD83C\uDF55",title:"Pizza"},{emoji:"\uD83C\uDF2D",title:"Hot Dog"},{emoji:"\uD83E\uDD6A",title:"Sandwich"},{emoji:"\uD83C\uDF2E",title:"Taco"},{emoji:"\uD83C\uDF2F",title:"Burrito"},{emoji:"\uD83E\uDED4",title:"Tamale"},{emoji:"\uD83E\uDD59",title:"Stuffed Flatbread"},{emoji:"\uD83E\uDDC6",title:"Falafel"},{emoji:"\uD83E\uDD5A",title:"Egg"},{emoji:"\uD83C\uDF73",title:"Cooking"},{emoji:"\uD83E\uDD58",title:"Shallow Pan of Food"},{emoji:"\uD83C\uDF72",title:"Pot of Food"},{emoji:"\uD83E\uDED5",title:"Fondue"},{emoji:"\uD83E\uDD63",title:"Bowl with Spoon"},{emoji:"\uD83E\uDD57",title:"Green Salad"},{emoji:"\uD83C\uDF7F",title:"Popcorn"},{emoji:"\uD83E\uDDC8",title:"Butter"},{emoji:"\uD83E\uDDC2",title:"Salt"},{emoji:"\uD83E\uDD6B",title:"Canned Food"},{emoji:"\uD83C\uDF71",title:"Bento Box"},{emoji:"\uD83C\uDF58",title:"Rice Cracker"},{emoji:"\uD83C\uDF59",title:"Rice Ball"},{emoji:"\uD83C\uDF5A",title:"Cooked Rice"},{emoji:"\uD83C\uDF5B",title:"Curry Rice"},{emoji:"\uD83C\uDF5C",title:"Steaming Bowl"},{emoji:"\uD83C\uDF5D",title:"Spaghetti"},{emoji:"\uD83C\uDF60",title:"Roasted Sweet Potato"},{emoji:"\uD83C\uDF62",title:"Oden"},{emoji:"\uD83C\uDF63",title:"Sushi"},{emoji:"\uD83C\uDF64",title:"Fried Shrimp"},{emoji:"\uD83C\uDF65",title:"Fish Cake with Swirl"},{emoji:"\uD83E\uDD6E",title:"Moon Cake"},{emoji:"\uD83C\uDF61",title:"Dango"},{emoji:"\uD83E\uDD5F",title:"Dumpling"},{emoji:"\uD83E\uDD60",title:"Fortune Cookie"},{emoji:"\uD83E\uDD61",title:"Takeout Box"},{emoji:"\uD83E\uDDAA",title:"Oyster"},{emoji:"\uD83C\uDF66",title:"Soft Ice Cream"},{emoji:"\uD83C\uDF67",title:"Shaved Ice"},{emoji:"\uD83C\uDF68",title:"Ice Cream"},{emoji:"\uD83C\uDF69",title:"Doughnut"},{emoji:"\uD83C\uDF6A",title:"Cookie"},{emoji:"\uD83C\uDF82",title:"Birthday Cake"},{emoji:"\uD83C\uDF70",title:"Shortcake"},{emoji:"\uD83E\uDDC1",title:"Cupcake"},{emoji:"\uD83E\uDD67",title:"Pie"},{emoji:"\uD83C\uDF6B",title:"Chocolate Bar"},{emoji:"\uD83C\uDF6C",title:"Candy"},{emoji:"\uD83C\uDF6D",title:"Lollipop"},{emoji:"\uD83C\uDF6E",title:"Custard"},{emoji:"\uD83C\uDF6F",title:"Honey Pot"},{emoji:"\uD83C\uDF7C",title:"Baby Bottle"},{emoji:"\uD83E\uDD5B",title:"Glass of Milk"},{emoji:"☕",title:"Hot Beverage"},{emoji:"\uD83E\uDED6",title:"Teapot"},{emoji:"\uD83C\uDF75",title:"Teacup Without Handle"},{emoji:"\uD83C\uDF76",title:"Sake"},{emoji:"\uD83C\uDF7E",title:"Bottle with Popping Cork"},{emoji:"\uD83C\uDF77",title:"Wine Glass"},{emoji:"\uD83C\uDF78",title:"Cocktail Glass"},{emoji:"\uD83C\uDF79",title:"Tropical Drink"},{emoji:"\uD83C\uDF7A",title:"Beer Mug"},{emoji:"\uD83C\uDF7B",title:"Clinking Beer Mugs"},{emoji:"\uD83E\uDD42",title:"Clinking Glasses"},{emoji:"\uD83E\uDD43",title:"Tumbler Glass"},{emoji:"\uD83E\uDD64",title:"Cup with Straw"},{emoji:"\uD83E\uDDCB",title:"Bubble Tea"},{emoji:"\uD83E\uDDC3",title:"Beverage Box"},{emoji:"\uD83E\uDDC9",title:"Mate"},{emoji:"\uD83E\uDDCA",title:"Ice"},{emoji:"\uD83E\uDD62",title:"Chopsticks"},{emoji:"\uD83C\uDF7D️",title:"Fork and Knife with Plate"},{emoji:"\uD83C\uDF74",title:"Fork and Knife"},{emoji:"\uD83E\uDD44",title:"Spoon"}],Activity:[{emoji:"\uD83D\uDD74️",title:"Person in Suit Levitating"},{emoji:"\uD83E\uDDD7",title:"Person Climbing"},{emoji:"\uD83E\uDDD7‍♂️",title:"Man Climbing"},{emoji:"\uD83E\uDDD7‍♀️",title:"Woman Climbing"},{emoji:"\uD83E\uDD3A",title:"Person Fencing"},{emoji:"\uD83C\uDFC7",title:"Horse Racing"},{emoji:"⛷️",title:"Skier"},{emoji:"\uD83C\uDFC2",title:"Snowboarder"},{emoji:"\uD83C\uDFCC️",title:"Person Golfing"},{emoji:"\uD83C\uDFCC️‍♂️",title:"Man Golfing"},{emoji:"\uD83C\uDFCC️‍♀️",title:"Woman Golfing"},{emoji:"\uD83C\uDFC4",title:"Person Surfing"},{emoji:"\uD83C\uDFC4‍♂️",title:"Man Surfing"},{emoji:"\uD83C\uDFC4‍♀️",title:"Woman Surfing"},{emoji:"\uD83D\uDEA3",title:"Person Rowing Boat"},{emoji:"\uD83D\uDEA3‍♂️",title:"Man Rowing Boat"},{emoji:"\uD83D\uDEA3‍♀️",title:"Woman Rowing Boat"},{emoji:"\uD83C\uDFCA",title:"Person Swimming"},{emoji:"\uD83C\uDFCA‍♂️",title:"Man Swimming"},{emoji:"\uD83C\uDFCA‍♀️",title:"Woman Swimming"},{emoji:"⛹️",title:"Person Bouncing Ball"},{emoji:"⛹️‍♂️",title:"Man Bouncing Ball"},{emoji:"⛹️‍♀️",title:"Woman Bouncing Ball"},{emoji:"\uD83C\uDFCB️",title:"Person Lifting Weights"},{emoji:"\uD83C\uDFCB️‍♂️",title:"Man Lifting Weights"},{emoji:"\uD83C\uDFCB️‍♀️",title:"Woman Lifting Weights"},{emoji:"\uD83D\uDEB4",title:"Person Biking"},{emoji:"\uD83D\uDEB4‍♂️",title:"Man Biking"},{emoji:"\uD83D\uDEB4‍♀️",title:"Woman Biking"},{emoji:"\uD83D\uDEB5",title:"Person Mountain Biking"},{emoji:"\uD83D\uDEB5‍♂️",title:"Man Mountain Biking"},{emoji:"\uD83D\uDEB5‍♀️",title:"Woman Mountain Biking"},{emoji:"\uD83E\uDD38",title:"Person Cartwheeling"},{emoji:"\uD83E\uDD38‍♂️",title:"Man Cartwheeling"},{emoji:"\uD83E\uDD38‍♀️",title:"Woman Cartwheeling"},{emoji:"\uD83E\uDD3C",title:"People Wrestling"},{emoji:"\uD83E\uDD3C‍♂️",title:"Men Wrestling"},{emoji:"\uD83E\uDD3C‍♀️",title:"Women Wrestling"},{emoji:"\uD83E\uDD3D",title:"Person Playing Water Polo"},{emoji:"\uD83E\uDD3D‍♂️",title:"Man Playing Water Polo"},{emoji:"\uD83E\uDD3D‍♀️",title:"Woman Playing Water Polo"},{emoji:"\uD83E\uDD3E",title:"Person Playing Handball"},{emoji:"\uD83E\uDD3E‍♂️",title:"Man Playing Handball"},{emoji:"\uD83E\uDD3E‍♀️",title:"Woman Playing Handball"},{emoji:"\uD83E\uDD39",title:"Person Juggling"},{emoji:"\uD83E\uDD39‍♂️",title:"Man Juggling"},{emoji:"\uD83E\uDD39‍♀️",title:"Woman Juggling"},{emoji:"\uD83E\uDDD8",title:"Person in Lotus Position"},{emoji:"\uD83E\uDDD8‍♂️",title:"Man in Lotus Position"},{emoji:"\uD83E\uDDD8‍♀️",title:"Woman in Lotus Position"},{emoji:"\uD83C\uDFAA",title:"Circus Tent"},{emoji:"\uD83D\uDEF9",title:"Skateboard"},{emoji:"\uD83D\uDEFC",title:"Roller Skate"},{emoji:"\uD83D\uDEF6",title:"Canoe"},{emoji:"\uD83C\uDF97️",title:"Reminder Ribbon"},{emoji:"\uD83C\uDF9F️",title:"Admission Tickets"},{emoji:"\uD83C\uDFAB",title:"Ticket"},{emoji:"\uD83C\uDF96️",title:"Military Medal"},{emoji:"\uD83C\uDFC6",title:"Trophy"},{emoji:"\uD83C\uDFC5",title:"Sports Medal"},{emoji:"\uD83E\uDD47",title:"1st Place Medal"},{emoji:"\uD83E\uDD48",title:"2nd Place Medal"},{emoji:"\uD83E\uDD49",title:"3rd Place Medal"},{emoji:"⚽",title:"Soccer Ball"},{emoji:"⚾",title:"Baseball"},{emoji:"\uD83E\uDD4E",title:"Softball"},{emoji:"\uD83C\uDFC0",title:"Basketball"},{emoji:"\uD83C\uDFD0",title:"Volleyball"},{emoji:"\uD83C\uDFC8",title:"American Football"},{emoji:"\uD83C\uDFC9",title:"Rugby Football"},{emoji:"\uD83C\uDFBE",title:"Tennis"},{emoji:"\uD83E\uDD4F",title:"Flying Disc"},{emoji:"\uD83C\uDFB3",title:"Bowling"},{emoji:"\uD83C\uDFCF",title:"Cricket Game"},{emoji:"\uD83C\uDFD1",title:"Field Hockey"},{emoji:"\uD83C\uDFD2",title:"Ice Hockey"},{emoji:"\uD83E\uDD4D",title:"Lacrosse"},{emoji:"\uD83C\uDFD3",title:"Ping Pong"},{emoji:"\uD83C\uDFF8",title:"Badminton"},{emoji:"\uD83E\uDD4A",title:"Boxing Glove"},{emoji:"\uD83E\uDD4B",title:"Martial Arts Uniform"},{emoji:"\uD83E\uDD45",title:"Goal Net"},{emoji:"⛳",title:"Flag in Hole"},{emoji:"⛸️",title:"Ice Skate"},{emoji:"\uD83C\uDFA3",title:"Fishing Pole"},{emoji:"\uD83C\uDFBD",title:"Running Shirt"},{emoji:"\uD83C\uDFBF",title:"Skis"},{emoji:"\uD83D\uDEF7",title:"Sled"},{emoji:"\uD83E\uDD4C",title:"Curling Stone"},{emoji:"\uD83C\uDFAF",title:"Bullseye"},{emoji:"\uD83C\uDFB1",title:"Pool 8 Ball"},{emoji:"\uD83C\uDFAE",title:"Video Game"},{emoji:"\uD83C\uDFB0",title:"Slot Machine"},{emoji:"\uD83C\uDFB2",title:"Game Die"},{emoji:"\uD83E\uDDE9",title:"Puzzle Piece"},{emoji:"♟️",title:"Chess Pawn"},{emoji:"\uD83C\uDFAD",title:"Performing Arts"},{emoji:"\uD83C\uDFA8",title:"Artist Palette"},{emoji:"\uD83E\uDDF5",title:"Thread"},{emoji:"\uD83E\uDDF6",title:"Yarn"},{emoji:"\uD83C\uDFBC",title:"Musical Score"},{emoji:"\uD83C\uDFA4",title:"Microphone"},{emoji:"\uD83C\uDFA7",title:"Headphone"},{emoji:"\uD83C\uDFB7",title:"Saxophone"},{emoji:"\uD83E\uDE97",title:"Accordion"},{emoji:"\uD83C\uDFB8",title:"Guitar"},{emoji:"\uD83C\uDFB9",title:"Musical Keyboard"},{emoji:"\uD83C\uDFBA",title:"Trumpet"},{emoji:"\uD83C\uDFBB",title:"Violin"},{emoji:"\uD83E\uDD41",title:"Drum"},{emoji:"\uD83E\uDE98",title:"Long Drum"},{emoji:"\uD83C\uDFAC",title:"Clapper Board"},{emoji:"\uD83C\uDFF9",title:"Bow and Arrow"}],"Travel-places":[{emoji:"\uD83D\uDEA3",title:"Person Rowing Boat"},{emoji:"\uD83D\uDDFE",title:"Map of Japan"},{emoji:"\uD83C\uDFD4️",title:"Snow-Capped Mountain"},{emoji:"⛰️",title:"Mountain"},{emoji:"\uD83C\uDF0B",title:"Volcano"},{emoji:"\uD83D\uDDFB",title:"Mount Fuji"},{emoji:"\uD83C\uDFD5️",title:"Camping"},{emoji:"\uD83C\uDFD6️",title:"Beach with Umbrella"},{emoji:"\uD83C\uDFDC️",title:"Desert"},{emoji:"\uD83C\uDFDD️",title:"Desert Island"},{emoji:"\uD83C\uDFDE️",title:"National Park"},{emoji:"\uD83C\uDFDF️",title:"Stadium"},{emoji:"\uD83C\uDFDB️",title:"Classical Building"},{emoji:"\uD83C\uDFD7️",title:"Building Construction"},{emoji:"\uD83D\uDED6",title:"Hut"},{emoji:"\uD83C\uDFD8️",title:"Houses"},{emoji:"\uD83C\uDFDA️",title:"Derelict House"},{emoji:"\uD83C\uDFE0",title:"House"},{emoji:"\uD83C\uDFE1",title:"House with Garden"},{emoji:"\uD83C\uDFE2",title:"Office Building"},{emoji:"\uD83C\uDFE3",title:"Japanese Post Office"},{emoji:"\uD83C\uDFE4",title:"Post Office"},{emoji:"\uD83C\uDFE5",title:"Hospital"},{emoji:"\uD83C\uDFE6",title:"Bank"},{emoji:"\uD83C\uDFE8",title:"Hotel"},{emoji:"\uD83C\uDFE9",title:"Love Hotel"},{emoji:"\uD83C\uDFEA",title:"Convenience Store"},{emoji:"\uD83C\uDFEB",title:"School"},{emoji:"\uD83C\uDFEC",title:"Department Store"},{emoji:"\uD83C\uDFED",title:"Factory"},{emoji:"\uD83C\uDFEF",title:"Japanese Castle"},{emoji:"\uD83C\uDFF0",title:"Castle"},{emoji:"\uD83D\uDC92",title:"Wedding"},{emoji:"\uD83D\uDDFC",title:"Tokyo Tower"},{emoji:"\uD83D\uDDFD",title:"Statue of Liberty"},{emoji:"⛪",title:"Church"},{emoji:"\uD83D\uDD4C",title:"Mosque"},{emoji:"\uD83D\uDED5",title:"Hindu Temple"},{emoji:"\uD83D\uDD4D",title:"Synagogue"},{emoji:"⛩️",title:"Shinto Shrine"},{emoji:"\uD83D\uDD4B",title:"Kaaba"},{emoji:"⛲",title:"Fountain"},{emoji:"⛺",title:"Tent"},{emoji:"\uD83C\uDF01",title:"Foggy"},{emoji:"\uD83C\uDF03",title:"Night with Stars"},{emoji:"\uD83C\uDFD9️",title:"Cityscape"},{emoji:"\uD83C\uDF04",title:"Sunrise Over Mountains"},{emoji:"\uD83C\uDF05",title:"Sunrise"},{emoji:"\uD83C\uDF06",title:"Cityscape at Dusk"},{emoji:"\uD83C\uDF07",title:"Sunset"},{emoji:"\uD83C\uDF09",title:"Bridge at Night"},{emoji:"\uD83C\uDFA0",title:"Carousel Horse"},{emoji:"\uD83C\uDFA1",title:"Ferris Wheel"},{emoji:"\uD83C\uDFA2",title:"Roller Coaster"},{emoji:"\uD83D\uDE82",title:"Locomotive"},{emoji:"\uD83D\uDE83",title:"Railway Car"},{emoji:"\uD83D\uDE84",title:"High-Speed Train"},{emoji:"\uD83D\uDE85",title:"Bullet Train"},{emoji:"\uD83D\uDE86",title:"Train"},{emoji:"\uD83D\uDE87",title:"Metro"},{emoji:"\uD83D\uDE88",title:"Light Rail"},{emoji:"\uD83D\uDE89",title:"Station"},{emoji:"\uD83D\uDE8A",title:"Tram"},{emoji:"\uD83D\uDE9D",title:"Monorail"},{emoji:"\uD83D\uDE9E",title:"Mountain Railway"},{emoji:"\uD83D\uDE8B",title:"Tram Car"},{emoji:"\uD83D\uDE8C",title:"Bus"},{emoji:"\uD83D\uDE8D",title:"Oncoming Bus"},{emoji:"\uD83D\uDE8E",title:"Trolleybus"},{emoji:"\uD83D\uDE90",title:"Minibus"},{emoji:"\uD83D\uDE91",title:"Ambulance"},{emoji:"\uD83D\uDE92",title:"Fire Engine"},{emoji:"\uD83D\uDE93",title:"Police Car"},{emoji:"\uD83D\uDE94",title:"Oncoming Police Car"},{emoji:"\uD83D\uDE95",title:"Taxi"},{emoji:"\uD83D\uDE96",title:"Oncoming Taxi"},{emoji:"\uD83D\uDE97",title:"Automobile"},{emoji:"\uD83D\uDE98",title:"Oncoming Automobile"},{emoji:"\uD83D\uDE99",title:"Sport Utility Vehicle"},{emoji:"\uD83D\uDEFB",title:"Pickup Truck"},{emoji:"\uD83D\uDE9A",title:"Delivery Truck"},{emoji:"\uD83D\uDE9B",title:"Articulated Lorry"},{emoji:"\uD83D\uDE9C",title:"Tractor"},{emoji:"\uD83C\uDFCE️",title:"Racing Car"},{emoji:"\uD83C\uDFCD️",title:"Motorcycle"},{emoji:"\uD83D\uDEF5",title:"Motor Scooter"},{emoji:"\uD83D\uDEFA",title:"Auto Rickshaw"},{emoji:"\uD83D\uDEB2",title:"Bicycle"},{emoji:"\uD83D\uDEF4",title:"Kick Scooter"},{emoji:"\uD83D\uDE8F",title:"Bus Stop"},{emoji:"\uD83D\uDEE3️",title:"Motorway"},{emoji:"\uD83D\uDEE4️",title:"Railway Track"},{emoji:"⛽",title:"Fuel Pump"},{emoji:"\uD83D\uDEA8",title:"Police Car Light"},{emoji:"\uD83D\uDEA5",title:"Horizontal Traffic Light"},{emoji:"\uD83D\uDEA6",title:"Vertical Traffic Light"},{emoji:"\uD83D\uDEA7",title:"Construction"},{emoji:"⚓",title:"Anchor"},{emoji:"⛵",title:"Sailboat"},{emoji:"\uD83D\uDEA4",title:"Speedboat"},{emoji:"\uD83D\uDEF3️",title:"Passenger Ship"},{emoji:"⛴️",title:"Ferry"},{emoji:"\uD83D\uDEE5️",title:"Motor Boat"},{emoji:"\uD83D\uDEA2",title:"Ship"},{emoji:"✈️",title:"Airplane"},{emoji:"\uD83D\uDEE9️",title:"Small Airplane"},{emoji:"\uD83D\uDEEB",title:"Airplane Departure"},{emoji:"\uD83D\uDEEC",title:"Airplane Arrival"},{emoji:"\uD83E\uDE82",title:"Parachute"},{emoji:"\uD83D\uDCBA",title:"Seat"},{emoji:"\uD83D\uDE81",title:"Helicopter"},{emoji:"\uD83D\uDE9F",title:"Suspension Railway"},{emoji:"\uD83D\uDEA0",title:"Mountain Cableway"},{emoji:"\uD83D\uDEA1",title:"Aerial Tramway"},{emoji:"\uD83D\uDEF0️",title:"Satellite"},{emoji:"\uD83D\uDE80",title:"Rocket"},{emoji:"\uD83D\uDEF8",title:"Flying Saucer"},{emoji:"\uD83E\uDE90",title:"Ringed Planet"},{emoji:"\uD83C\uDF20",title:"Shooting Star"},{emoji:"\uD83C\uDF0C",title:"Milky Way"},{emoji:"⛱️",title:"Umbrella on Ground"},{emoji:"\uD83C\uDF86",title:"Fireworks"},{emoji:"\uD83C\uDF87",title:"Sparkler"},{emoji:"\uD83C\uDF91",title:"Moon Viewing Ceremony"},{emoji:"\uD83D\uDCB4",title:"Yen Banknote"},{emoji:"\uD83D\uDCB5",title:"Dollar Banknote"},{emoji:"\uD83D\uDCB6",title:"Euro Banknote"},{emoji:"\uD83D\uDCB7",title:"Pound Banknote"},{emoji:"\uD83D\uDDFF",title:"Moai"},{emoji:"\uD83D\uDEC2",title:"Passport Control"},{emoji:"\uD83D\uDEC3",title:"Customs"},{emoji:"\uD83D\uDEC4",title:"Baggage Claim"},{emoji:"\uD83D\uDEC5",title:"Left Luggage"}],Objects:[{emoji:"\uD83D\uDC8C",title:"Love Letter"},{emoji:"\uD83D\uDD73️",title:"Hole"},{emoji:"\uD83D\uDCA3",title:"Bomb"},{emoji:"\uD83D\uDEC0",title:"Person Taking Bath"},{emoji:"\uD83D\uDECC",title:"Person in Bed"},{emoji:"\uD83D\uDD2A",title:"Kitchen Knife"},{emoji:"\uD83C\uDFFA",title:"Amphora"},{emoji:"\uD83D\uDDFA️",title:"World Map"},{emoji:"\uD83E\uDDED",title:"Compass"},{emoji:"\uD83E\uDDF1",title:"Brick"},{emoji:"\uD83D\uDC88",title:"Barber Pole"},{emoji:"\uD83E\uDDBD",title:"Manual Wheelchair"},{emoji:"\uD83E\uDDBC",title:"Motorized Wheelchair"},{emoji:"\uD83D\uDEE2️",title:"Oil Drum"},{emoji:"\uD83D\uDECE️",title:"Bellhop Bell"},{emoji:"\uD83E\uDDF3",title:"Luggage"},{emoji:"⌛",title:"Hourglass Done"},{emoji:"⏳",title:"Hourglass Not Done"},{emoji:"⌚",title:"Watch"},{emoji:"⏰",title:"Alarm Clock"},{emoji:"⏱️",title:"Stopwatch"},{emoji:"⏲️",title:"Timer Clock"},{emoji:"\uD83D\uDD70️",title:"Mantelpiece Clock"},{emoji:"\uD83C\uDF21️",title:"Thermometer"},{emoji:"⛱️",title:"Umbrella on Ground"},{emoji:"\uD83E\uDDE8",title:"Firecracker"},{emoji:"\uD83C\uDF88",title:"Balloon"},{emoji:"\uD83C\uDF89",title:"Party Popper"},{emoji:"\uD83C\uDF8A",title:"Confetti Ball"},{emoji:"\uD83C\uDF8E",title:"Japanese Dolls"},{emoji:"\uD83C\uDF8F",title:"Carp Streamer"},{emoji:"\uD83C\uDF90",title:"Wind Chime"},{emoji:"\uD83E\uDDE7",title:"Red Envelope"},{emoji:"\uD83C\uDF80",title:"Ribbon"},{emoji:"\uD83C\uDF81",title:"Wrapped Gift"},{emoji:"\uD83E\uDD3F",title:"Diving Mask"},{emoji:"\uD83E\uDE80",title:"Yo-Yo"},{emoji:"\uD83E\uDE81",title:"Kite"},{emoji:"\uD83D\uDD2E",title:"Crystal Ball"},{emoji:"\uD83E\uDE84",title:"Magic Wand"},{emoji:"\uD83E\uDDFF",title:"Nazar Amulet"},{emoji:"\uD83D\uDD79️",title:"Joystick"},{emoji:"\uD83E\uDDF8",title:"Teddy Bear"},{emoji:"\uD83E\uDE85",title:"Pi\xf1ata"},{emoji:"\uD83E\uDE86",title:"Nesting Dolls"},{emoji:"\uD83D\uDDBC️",title:"Framed Picture"},{emoji:"\uD83E\uDDF5",title:"Thread"},{emoji:"\uD83E\uDEA1",title:"Sewing Needle"},{emoji:"\uD83E\uDDF6",title:"Yarn"},{emoji:"\uD83E\uDEA2",title:"Knot"},{emoji:"\uD83D\uDECD️",title:"Shopping Bags"},{emoji:"\uD83D\uDCFF",title:"Prayer Beads"},{emoji:"\uD83D\uDC8E",title:"Gem Stone"},{emoji:"\uD83D\uDCEF",title:"Postal Horn"},{emoji:"\uD83C\uDF99️",title:"Studio Microphone"},{emoji:"\uD83C\uDF9A️",title:"Level Slider"},{emoji:"\uD83C\uDF9B️",title:"Control Knobs"},{emoji:"\uD83D\uDCFB",title:"Radio"},{emoji:"\uD83E\uDE95",title:"Banjo"},{emoji:"\uD83D\uDCF1",title:"Mobile Phone"},{emoji:"\uD83D\uDCF2",title:"Mobile Phone with Arrow"},{emoji:"☎️",title:"Telephone"},{emoji:"\uD83D\uDCDE",title:"Telephone Receiver"},{emoji:"\uD83D\uDCDF",title:"Pager"},{emoji:"\uD83D\uDCE0",title:"Fax Machine"},{emoji:"\uD83D\uDD0B",title:"Battery"},{emoji:"\uD83D\uDD0C",title:"Electric Plug"},{emoji:"\uD83D\uDCBB",title:"Laptop"},{emoji:"\uD83D\uDDA5️",title:"Desktop Computer"},{emoji:"\uD83D\uDDA8️",title:"Printer"},{emoji:"⌨️",title:"Keyboard"},{emoji:"\uD83D\uDDB1️",title:"Computer Mouse"},{emoji:"\uD83D\uDDB2️",title:"Trackball"},{emoji:"\uD83D\uDCBD",title:"Computer Disk"},{emoji:"\uD83D\uDCBE",title:"Floppy Disk"},{emoji:"\uD83D\uDCBF",title:"Optical Disk"},{emoji:"\uD83D\uDCC0",title:"DVD"},{emoji:"\uD83E\uDDEE",title:"Abacus"},{emoji:"\uD83C\uDFA5",title:"Movie Camera"},{emoji:"\uD83C\uDF9E️",title:"Film Frames"},{emoji:"\uD83D\uDCFD️",title:"Film Projector"},{emoji:"\uD83D\uDCFA",title:"Television"},{emoji:"\uD83D\uDCF7",title:"Camera"},{emoji:"\uD83D\uDCF8",title:"Camera with Flash"},{emoji:"\uD83D\uDCF9",title:"Video Camera"},{emoji:"\uD83D\uDCFC",title:"Videocassette"},{emoji:"\uD83D\uDD0D",title:"Magnifying Glass Tilted Left"},{emoji:"\uD83D\uDD0E",title:"Magnifying Glass Tilted Right"},{emoji:"\uD83D\uDD6F️",title:"Candle"},{emoji:"\uD83D\uDCA1",title:"Light Bulb"},{emoji:"\uD83D\uDD26",title:"Flashlight"},{emoji:"\uD83C\uDFEE",title:"Red Paper Lantern"},{emoji:"\uD83E\uDE94",title:"Diya Lamp"},{emoji:"\uD83D\uDCD4",title:"Notebook with Decorative Cover"},{emoji:"\uD83D\uDCD5",title:"Closed Book"},{emoji:"\uD83D\uDCD6",title:"Open Book"},{emoji:"\uD83D\uDCD7",title:"Green Book"},{emoji:"\uD83D\uDCD8",title:"Blue Book"},{emoji:"\uD83D\uDCD9",title:"Orange Book"},{emoji:"\uD83D\uDCDA",title:"Books"},{emoji:"\uD83D\uDCD3",title:"Notebook"},{emoji:"\uD83D\uDCD2",title:"Ledger"},{emoji:"\uD83D\uDCC3",title:"Page with Curl"},{emoji:"\uD83D\uDCDC",title:"Scroll"},{emoji:"\uD83D\uDCC4",title:"Page Facing Up"},{emoji:"\uD83D\uDCF0",title:"Newspaper"},{emoji:"\uD83D\uDDDE️",title:"Rolled-Up Newspaper"},{emoji:"\uD83D\uDCD1",title:"Bookmark Tabs"},{emoji:"\uD83D\uDD16",title:"Bookmark"},{emoji:"\uD83C\uDFF7️",title:"Label"},{emoji:"\uD83D\uDCB0",title:"Money Bag"},{emoji:"\uD83E\uDE99",title:"Coin"},{emoji:"\uD83D\uDCB4",title:"Yen Banknote"},{emoji:"\uD83D\uDCB5",title:"Dollar Banknote"},{emoji:"\uD83D\uDCB6",title:"Euro Banknote"},{emoji:"\uD83D\uDCB7",title:"Pound Banknote"},{emoji:"\uD83D\uDCB8",title:"Money with Wings"},{emoji:"\uD83D\uDCB3",title:"Credit Card"},{emoji:"\uD83E\uDDFE",title:"Receipt"},{emoji:"✉️",title:"Envelope"},{emoji:"\uD83D\uDCE7",title:"E-Mail"},{emoji:"\uD83D\uDCE8",title:"Incoming Envelope"},{emoji:"\uD83D\uDCE9",title:"Envelope with Arrow"},{emoji:"\uD83D\uDCE4",title:"Outbox Tray"},{emoji:"\uD83D\uDCE5",title:"Inbox Tray"},{emoji:"\uD83D\uDCE6",title:"Package"},{emoji:"\uD83D\uDCEB",title:"Closed Mailbox with Raised Flag"},{emoji:"\uD83D\uDCEA",title:"Closed Mailbox with Lowered Flag"},{emoji:"\uD83D\uDCEC",title:"Open Mailbox with Raised Flag"},{emoji:"\uD83D\uDCED",title:"Open Mailbox with Lowered Flag"},{emoji:"\uD83D\uDCEE",title:"Postbox"},{emoji:"\uD83D\uDDF3️",title:"Ballot Box with Ballot"},{emoji:"✏️",title:"Pencil"},{emoji:"✒️",title:"Black Nib"},{emoji:"\uD83D\uDD8B️",title:"Fountain Pen"},{emoji:"\uD83D\uDD8A️",title:"Pen"},{emoji:"\uD83D\uDD8C️",title:"Paintbrush"},{emoji:"\uD83D\uDD8D️",title:"Crayon"},{emoji:"\uD83D\uDCDD",title:"Memo"},{emoji:"\uD83D\uDCC1",title:"File Folder"},{emoji:"\uD83D\uDCC2",title:"Open File Folder"},{emoji:"\uD83D\uDDC2️",title:"Card Index Dividers"},{emoji:"\uD83D\uDCC5",title:"Calendar"},{emoji:"\uD83D\uDCC6",title:"Tear-Off Calendar"},{emoji:"\uD83D\uDDD2️",title:"Spiral Notepad"},{emoji:"\uD83D\uDDD3️",title:"Spiral Calendar"},{emoji:"\uD83D\uDCC7",title:"Card Index"},{emoji:"\uD83D\uDCC8",title:"Chart Increasing"},{emoji:"\uD83D\uDCC9",title:"Chart Decreasing"},{emoji:"\uD83D\uDCCA",title:"Bar Chart"},{emoji:"\uD83D\uDCCB",title:"Clipboard"},{emoji:"\uD83D\uDCCC",title:"Pushpin"},{emoji:"\uD83D\uDCCD",title:"Round Pushpin"},{emoji:"\uD83D\uDCCE",title:"Paperclip"},{emoji:"\uD83D\uDD87️",title:"Linked Paperclips"},{emoji:"\uD83D\uDCCF",title:"Straight Ruler"},{emoji:"\uD83D\uDCD0",title:"Triangular Ruler"},{emoji:"✂️",title:"Scissors"},{emoji:"\uD83D\uDDC3️",title:"Card File Box"},{emoji:"\uD83D\uDDC4️",title:"File Cabinet"},{emoji:"\uD83D\uDDD1️",title:"Wastebasket"},{emoji:"\uD83D\uDD12",title:"Locked"},{emoji:"\uD83D\uDD13",title:"Unlocked"},{emoji:"\uD83D\uDD0F",title:"Locked with Pen"},{emoji:"\uD83D\uDD10",title:"Locked with Key"},{emoji:"\uD83D\uDD11",title:"Key"},{emoji:"\uD83D\uDDDD️",title:"Old Key"},{emoji:"\uD83D\uDD28",title:"Hammer"},{emoji:"\uD83E\uDE93",title:"Axe"},{emoji:"⛏️",title:"Pick"},{emoji:"⚒️",title:"Hammer and Pick"},{emoji:"\uD83D\uDEE0️",title:"Hammer and Wrench"},{emoji:"\uD83D\uDDE1️",title:"Dagger"},{emoji:"⚔️",title:"Crossed Swords"},{emoji:"\uD83D\uDD2B",title:"Water Pistol"},{emoji:"\uD83E\uDE83",title:"Boomerang"},{emoji:"\uD83D\uDEE1️",title:"Shield"},{emoji:"\uD83E\uDE9A",title:"Carpentry Saw"},{emoji:"\uD83D\uDD27",title:"Wrench"},{emoji:"\uD83E\uDE9B",title:"Screwdriver"},{emoji:"\uD83D\uDD29",title:"Nut and Bolt"},{emoji:"⚙️",title:"Gear"},{emoji:"\uD83D\uDDDC️",title:"Clamp"},{emoji:"⚖️",title:"Balance Scale"},{emoji:"\uD83E\uDDAF",title:"White Cane"},{emoji:"\uD83D\uDD17",title:"Link"},{emoji:"⛓️",title:"Chains"},{emoji:"\uD83E\uDE9D",title:"Hook"},{emoji:"\uD83E\uDDF0",title:"Toolbox"},{emoji:"\uD83E\uDDF2",title:"Magnet"},{emoji:"\uD83E\uDE9C",title:"Ladder"},{emoji:"⚗️",title:"Alembic"},{emoji:"\uD83E\uDDEA",title:"Test Tube"},{emoji:"\uD83E\uDDEB",title:"Petri Dish"},{emoji:"\uD83E\uDDEC",title:"DNA"},{emoji:"\uD83D\uDD2C",title:"Microscope"},{emoji:"\uD83D\uDD2D",title:"Telescope"},{emoji:"\uD83D\uDCE1",title:"Satellite Antenna"},{emoji:"\uD83D\uDC89",title:"Syringe"},{emoji:"\uD83E\uDE78",title:"Drop of Blood"},{emoji:"\uD83D\uDC8A",title:"Pill"},{emoji:"\uD83E\uDE79",title:"Adhesive Bandage"},{emoji:"\uD83E\uDE7A",title:"Stethoscope"},{emoji:"\uD83D\uDEAA",title:"Door"},{emoji:"\uD83E\uDE9E",title:"Mirror"},{emoji:"\uD83E\uDE9F",title:"Window"},{emoji:"\uD83D\uDECF️",title:"Bed"},{emoji:"\uD83D\uDECB️",title:"Couch and Lamp"},{emoji:"\uD83E\uDE91",title:"Chair"},{emoji:"\uD83D\uDEBD",title:"Toilet"},{emoji:"\uD83E\uDEA0",title:"Plunger"},{emoji:"\uD83D\uDEBF",title:"Shower"},{emoji:"\uD83D\uDEC1",title:"Bathtub"},{emoji:"\uD83E\uDEA4",title:"Mouse Trap"},{emoji:"\uD83E\uDE92",title:"Razor"},{emoji:"\uD83E\uDDF4",title:"Lotion Bottle"},{emoji:"\uD83E\uDDF7",title:"Safety Pin"},{emoji:"\uD83E\uDDF9",title:"Broom"},{emoji:"\uD83E\uDDFA",title:"Basket"},{emoji:"\uD83E\uDDFB",title:"Roll of Paper"},{emoji:"\uD83E\uDEA3",title:"Bucket"},{emoji:"\uD83E\uDDFC",title:"Soap"},{emoji:"\uD83E\uDEA5",title:"Toothbrush"},{emoji:"\uD83E\uDDFD",title:"Sponge"},{emoji:"\uD83E\uDDEF",title:"Fire Extinguisher"},{emoji:"\uD83D\uDED2",title:"Shopping Cart"},{emoji:"\uD83D\uDEAC",title:"Cigarette"},{emoji:"⚰️",title:"Coffin"},{emoji:"\uD83E\uDEA6",title:"Headstone"},{emoji:"⚱️",title:"Funeral Urn"},{emoji:"\uD83D\uDDFF",title:"Moai"},{emoji:"\uD83E\uDEA7",title:"Placard"},{emoji:"\uD83D\uDEB0",title:"Potable Water"}],Symbols:[{emoji:"\uD83D\uDC98",title:"Heart with Arrow"},{emoji:"\uD83D\uDC9D",title:"Heart with Ribbon"},{emoji:"\uD83D\uDC96",title:"Sparkling Heart"},{emoji:"\uD83D\uDC97",title:"Growing Heart"},{emoji:"\uD83D\uDC93",title:"Beating Heart"},{emoji:"\uD83D\uDC9E",title:"Revolving Hearts"},{emoji:"\uD83D\uDC95",title:"Two Hearts"},{emoji:"\uD83D\uDC9F",title:"Heart Decoration"},{emoji:"❣️",title:"Heart Exclamation"},{emoji:"\uD83D\uDC94",title:"Broken Heart"},{emoji:"❤️‍\uD83D\uDD25",title:"Heart on Fire"},{emoji:"❤️‍\uD83E\uDE79",title:"Mending Heart"},{emoji:"❤️",title:"Red Heart"},{emoji:"\uD83E\uDDE1",title:"Orange Heart"},{emoji:"\uD83D\uDC9B",title:"Yellow Heart"},{emoji:"\uD83D\uDC9A",title:"Green Heart"},{emoji:"\uD83D\uDC99",title:"Blue Heart"},{emoji:"\uD83D\uDC9C",title:"Purple Heart"},{emoji:"\uD83E\uDD0E",title:"Brown Heart"},{emoji:"\uD83D\uDDA4",title:"Black Heart"},{emoji:"\uD83E\uDD0D",title:"White Heart"},{emoji:"\uD83D\uDCAF",title:"Hundred Points"},{emoji:"\uD83D\uDCA2",title:"Anger Symbol"},{emoji:"\uD83D\uDCAC",title:"Speech Balloon"},{emoji:"\uD83D\uDC41️‍\uD83D\uDDE8️",title:"Eye in Speech Bubble"},{emoji:"\uD83D\uDDE8️",title:"Left Speech Bubble"},{emoji:"\uD83D\uDDEF️",title:"Right Anger Bubble"},{emoji:"\uD83D\uDCAD",title:"Thought Balloon"},{emoji:"\uD83D\uDCA4",title:"Zzz"},{emoji:"\uD83D\uDCAE",title:"White Flower"},{emoji:"♨️",title:"Hot Springs"},{emoji:"\uD83D\uDC88",title:"Barber Pole"},{emoji:"\uD83D\uDED1",title:"Stop Sign"},{emoji:"\uD83D\uDD5B",title:"Twelve O’Clock"},{emoji:"\uD83D\uDD67",title:"Twelve-Thirty"},{emoji:"\uD83D\uDD50",title:"One O’Clock"},{emoji:"\uD83D\uDD5C",title:"One-Thirty"},{emoji:"\uD83D\uDD51",title:"Two O’Clock"},{emoji:"\uD83D\uDD5D",title:"Two-Thirty"},{emoji:"\uD83D\uDD52",title:"Three O’Clock"},{emoji:"\uD83D\uDD5E",title:"Three-Thirty"},{emoji:"\uD83D\uDD53",title:"Four O’Clock"},{emoji:"\uD83D\uDD5F",title:"Four-Thirty"},{emoji:"\uD83D\uDD54",title:"Five O’Clock"},{emoji:"\uD83D\uDD60",title:"Five-Thirty"},{emoji:"\uD83D\uDD55",title:"Six O’Clock"},{emoji:"\uD83D\uDD61",title:"Six-Thirty"},{emoji:"\uD83D\uDD56",title:"Seven O’Clock"},{emoji:"\uD83D\uDD62",title:"Seven-Thirty"},{emoji:"\uD83D\uDD57",title:"Eight O’Clock"},{emoji:"\uD83D\uDD63",title:"Eight-Thirty"},{emoji:"\uD83D\uDD58",title:"Nine O’Clock"},{emoji:"\uD83D\uDD64",title:"Nine-Thirty"},{emoji:"\uD83D\uDD59",title:"Ten O’Clock"},{emoji:"\uD83D\uDD65",title:"Ten-Thirty"},{emoji:"\uD83D\uDD5A",title:"Eleven O’Clock"},{emoji:"\uD83D\uDD66",title:"Eleven-Thirty"},{emoji:"\uD83C\uDF00",title:"Cyclone"},{emoji:"♠️",title:"Spade Suit"},{emoji:"♥️",title:"Heart Suit"},{emoji:"♦️",title:"Diamond Suit"},{emoji:"♣️",title:"Club Suit"},{emoji:"\uD83C\uDCCF",title:"Joker"},{emoji:"\uD83C\uDC04",title:"Mahjong Red Dragon"},{emoji:"\uD83C\uDFB4",title:"Flower Playing Cards"},{emoji:"\uD83D\uDD07",title:"Muted Speaker"},{emoji:"\uD83D\uDD08",title:"Speaker Low Volume"},{emoji:"\uD83D\uDD09",title:"Speaker Medium Volume"},{emoji:"\uD83D\uDD0A",title:"Speaker High Volume"},{emoji:"\uD83D\uDCE2",title:"Loudspeaker"},{emoji:"\uD83D\uDCE3",title:"Megaphone"},{emoji:"\uD83D\uDCEF",title:"Postal Horn"},{emoji:"\uD83D\uDD14",title:"Bell"},{emoji:"\uD83D\uDD15",title:"Bell with Slash"},{emoji:"\uD83C\uDFB5",title:"Musical Note"},{emoji:"\uD83C\uDFB6",title:"Musical Notes"},{emoji:"\uD83D\uDCB9",title:"Chart Increasing with Yen"},{emoji:"\uD83D\uDED7",title:"Elevator"},{emoji:"\uD83C\uDFE7",title:"ATM Sign"},{emoji:"\uD83D\uDEAE",title:"Litter in Bin Sign"},{emoji:"\uD83D\uDEB0",title:"Potable Water"},{emoji:"♿",title:"Wheelchair Symbol"},{emoji:"\uD83D\uDEB9",title:"Men’s Room"},{emoji:"\uD83D\uDEBA",title:"Women’s Room"},{emoji:"\uD83D\uDEBB",title:"Restroom"},{emoji:"\uD83D\uDEBC",title:"Baby Symbol"},{emoji:"\uD83D\uDEBE",title:"Water Closet"},{emoji:"⚠️",title:"Warning"},{emoji:"\uD83D\uDEB8",title:"Children Crossing"},{emoji:"⛔",title:"No Entry"},{emoji:"\uD83D\uDEAB",title:"Prohibited"},{emoji:"\uD83D\uDEB3",title:"No Bicycles"},{emoji:"\uD83D\uDEAD",title:"No Smoking"},{emoji:"\uD83D\uDEAF",title:"No Littering"},{emoji:"\uD83D\uDEB1",title:"Non-Potable Water"},{emoji:"\uD83D\uDEB7",title:"No Pedestrians"},{emoji:"\uD83D\uDCF5",title:"No Mobile Phones"},{emoji:"\uD83D\uDD1E",title:"No One Under Eighteen"},{emoji:"☢️",title:"Radioactive"},{emoji:"☣️",title:"Biohazard"},{emoji:"⬆️",title:"Up Arrow"},{emoji:"↗️",title:"Up-Right Arrow"},{emoji:"➡️",title:"Right Arrow"},{emoji:"↘️",title:"Down-Right Arrow"},{emoji:"⬇️",title:"Down Arrow"},{emoji:"↙️",title:"Down-Left Arrow"},{emoji:"⬅️",title:"Left Arrow"},{emoji:"↖️",title:"Up-Left Arrow"},{emoji:"↕️",title:"Up-Down Arrow"},{emoji:"↔️",title:"Left-Right Arrow"},{emoji:"↩️",title:"Right Arrow Curving Left"},{emoji:"↪️",title:"Left Arrow Curving Right"},{emoji:"⤴️",title:"Right Arrow Curving Up"},{emoji:"⤵️",title:"Right Arrow Curving Down"},{emoji:"\uD83D\uDD03",title:"Clockwise Vertical Arrows"},{emoji:"\uD83D\uDD04",title:"Counterclockwise Arrows Button"},{emoji:"\uD83D\uDD19",title:"Back Arrow"},{emoji:"\uD83D\uDD1A",title:"End Arrow"},{emoji:"\uD83D\uDD1B",title:"On! Arrow"},{emoji:"\uD83D\uDD1C",title:"Soon Arrow"},{emoji:"\uD83D\uDD1D",title:"Top Arrow"},{emoji:"\uD83D\uDED0",title:"Place of Worship"},{emoji:"⚛️",title:"Atom Symbol"},{emoji:"\uD83D\uDD49️",title:"Om"},{emoji:"✡️",title:"Star of David"},{emoji:"☸️",title:"Wheel of Dharma"},{emoji:"☯️",title:"Yin Yang"},{emoji:"✝️",title:"Latin Cross"},{emoji:"☦️",title:"Orthodox Cross"},{emoji:"☪️",title:"Star and Crescent"},{emoji:"☮️",title:"Peace Symbol"},{emoji:"\uD83D\uDD4E",title:"Menorah"},{emoji:"\uD83D\uDD2F",title:"Dotted Six-Pointed Star"},{emoji:"♈",title:"Aries"},{emoji:"♉",title:"Taurus"},{emoji:"♊",title:"Gemini"},{emoji:"♋",title:"Cancer"},{emoji:"♌",title:"Leo"},{emoji:"♍",title:"Virgo"},{emoji:"♎",title:"Libra"},{emoji:"♏",title:"Scorpio"},{emoji:"♐",title:"Sagittarius"},{emoji:"♑",title:"Capricorn"},{emoji:"♒",title:"Aquarius"},{emoji:"♓",title:"Pisces"},{emoji:"⛎",title:"Ophiuchus"},{emoji:"\uD83D\uDD00",title:"Shuffle Tracks Button"},{emoji:"\uD83D\uDD01",title:"Repeat Button"},{emoji:"\uD83D\uDD02",title:"Repeat Single Button"},{emoji:"▶️",title:"Play Button"},{emoji:"⏩",title:"Fast-Forward Button"},{emoji:"⏭️",title:"Next Track Button"},{emoji:"⏯️",title:"Play or Pause Button"},{emoji:"◀️",title:"Reverse Button"},{emoji:"⏪",title:"Fast Reverse Button"},{emoji:"⏮️",title:"Last Track Button"},{emoji:"\uD83D\uDD3C",title:"Upwards Button"},{emoji:"⏫",title:"Fast Up Button"},{emoji:"\uD83D\uDD3D",title:"Downwards Button"},{emoji:"⏬",title:"Fast Down Button"},{emoji:"⏸️",title:"Pause Button"},{emoji:"⏹️",title:"Stop Button"},{emoji:"⏺️",title:"Record Button"},{emoji:"⏏️",title:"Eject Button"},{emoji:"\uD83C\uDFA6",title:"Cinema"},{emoji:"\uD83D\uDD05",title:"Dim Button"},{emoji:"\uD83D\uDD06",title:"Bright Button"},{emoji:"\uD83D\uDCF6",title:"Antenna Bars"},{emoji:"\uD83D\uDCF3",title:"Vibration Mode"},{emoji:"\uD83D\uDCF4",title:"Mobile Phone Off"},{emoji:"♀️",title:"Female Sign"},{emoji:"♂️",title:"Male Sign"},{emoji:"✖️",title:"Multiply"},{emoji:"➕",title:"Plus"},{emoji:"➖",title:"Minus"},{emoji:"➗",title:"Divide"},{emoji:"♾️",title:"Infinity"},{emoji:"‼️",title:"‼ Double Exclamation Mark"},{emoji:"⁉️",title:"⁉ Exclamation Question Mark"},{emoji:"❓",title:"Red Question Mark"},{emoji:"❔",title:"White Question Mark"},{emoji:"❕",title:"White Exclamation Mark"},{emoji:"❗",title:"Red Exclamation Mark"},{emoji:"〰️",title:"〰 Wavy Dash"},{emoji:"\uD83D\uDCB1",title:"Currency Exchange"},{emoji:"\uD83D\uDCB2",title:"Heavy Dollar Sign"},{emoji:"⚕️",title:"Medical Symbol"},{emoji:"♻️",title:"Recycling Symbol"},{emoji:"⚜️",title:"Fleur-de-lis"},{emoji:"\uD83D\uDD31",title:"Trident Emblem"},{emoji:"\uD83D\uDCDB",title:"Name Badge"},{emoji:"\uD83D\uDD30",title:"Japanese Symbol for Beginner"},{emoji:"⭕",title:"Hollow Red Circle"},{emoji:"✅",title:"Check Mark Button"},{emoji:"☑️",title:"Check Box with Check"},{emoji:"✔️",title:"Check Mark"},{emoji:"❌",title:"Cross Mark"},{emoji:"❎",title:"Cross Mark Button"},{emoji:"➰",title:"Curly Loop"},{emoji:"➿",title:"Double Curly Loop"},{emoji:"〽️",title:"〽 Part Alternation Mark"},{emoji:"✳️",title:"Eight-Spoked Asterisk"},{emoji:"✴️",title:"Eight-Pointed Star"},{emoji:"❇️",title:"Sparkle"},{emoji:"\xa9️",title:"Copyright"},{emoji:"\xae️",title:"Registered"},{emoji:"™️",title:"Trade Mark"},{emoji:"#️⃣",title:"# Keycap Number Sign"},{emoji:"*️⃣",title:"* Keycap Asterisk"},{emoji:"0️⃣",title:"0 Keycap Digit Zero"},{emoji:"1️⃣",title:"1 Keycap Digit One"},{emoji:"2️⃣",title:"2 Keycap Digit Two"},{emoji:"3️⃣",title:"3 Keycap Digit Three"},{emoji:"4️⃣",title:"4 Keycap Digit Four"},{emoji:"5️⃣",title:"5 Keycap Digit Five"},{emoji:"6️⃣",title:"6 Keycap Digit Six"},{emoji:"7️⃣",title:"7 Keycap Digit Seven"},{emoji:"8️⃣",title:"8 Keycap Digit Eight"},{emoji:"9️⃣",title:"9 Keycap Digit Nine"},{emoji:"\uD83D\uDD1F",title:"Keycap: 10"},{emoji:"\uD83D\uDD20",title:"Input Latin Uppercase"},{emoji:"\uD83D\uDD21",title:"Input Latin Lowercase"},{emoji:"\uD83D\uDD22",title:"Input Numbers"},{emoji:"\uD83D\uDD23",title:"Input Symbols"},{emoji:"\uD83D\uDD24",title:"Input Latin Letters"},{emoji:"\uD83C\uDD70️",title:"A Button (Blood Type)"},{emoji:"\uD83C\uDD8E",title:"AB Button (Blood Type)"},{emoji:"\uD83C\uDD71️",title:"B Button (Blood Type)"},{emoji:"\uD83C\uDD91",title:"CL Button"},{emoji:"\uD83C\uDD92",title:"Cool Button"},{emoji:"\uD83C\uDD93",title:"Free Button"},{emoji:"ℹ️",title:"ℹ Information"},{emoji:"\uD83C\uDD94",title:"ID Button"},{emoji:"Ⓜ️",title:"Circled M"},{emoji:"\uD83C\uDD95",title:"New Button"},{emoji:"\uD83C\uDD96",title:"NG Button"},{emoji:"\uD83C\uDD7E️",title:"O Button (Blood Type)"},{emoji:"\uD83C\uDD97",title:"OK Button"},{emoji:"\uD83C\uDD7F️",title:"P Button"},{emoji:"\uD83C\uDD98",title:"SOS Button"},{emoji:"\uD83C\uDD99",title:"Up! Button"},{emoji:"\uD83C\uDD9A",title:"Vs Button"},{emoji:"\uD83C\uDE01",title:"Japanese “Here” Button"},{emoji:"\uD83C\uDE02️",title:"Japanese “Service Charge” Button"},{emoji:"\uD83C\uDE37️",title:"Japanese “Monthly Amount” Button"},{emoji:"\uD83C\uDE36",title:"Japanese “Not Free of Charge” Button"},{emoji:"\uD83C\uDE2F",title:"Japanese “Reserved” Button"},{emoji:"\uD83C\uDE50",title:"Japanese “Bargain” Button"},{emoji:"\uD83C\uDE39",title:"Japanese “Discount” Button"},{emoji:"\uD83C\uDE1A",title:"Japanese “Free of Charge” Button"},{emoji:"\uD83C\uDE32",title:"Japanese “Prohibited” Button"},{emoji:"\uD83C\uDE51",title:"Japanese “Acceptable” Button"},{emoji:"\uD83C\uDE38",title:"Japanese “Application” Button"},{emoji:"\uD83C\uDE34",title:"Japanese “Passing Grade” Button"},{emoji:"\uD83C\uDE33",title:"Japanese “Vacancy” Button"},{emoji:"㊗️",title:"Japanese “Congratulations” Button"},{emoji:"㊙️",title:"Japanese “Secret” Button"},{emoji:"\uD83C\uDE3A",title:"Japanese “Open for Business” Button"},{emoji:"\uD83C\uDE35",title:"Japanese “No Vacancy” Button"},{emoji:"\uD83D\uDD34",title:"Red Circle"},{emoji:"\uD83D\uDFE0",title:"Orange Circle"},{emoji:"\uD83D\uDFE1",title:"Yellow Circle"},{emoji:"\uD83D\uDFE2",title:"Green Circle"},{emoji:"\uD83D\uDD35",title:"Blue Circle"},{emoji:"\uD83D\uDFE3",title:"Purple Circle"},{emoji:"\uD83D\uDFE4",title:"Brown Circle"},{emoji:"⚫",title:"Black Circle"},{emoji:"⚪",title:"White Circle"},{emoji:"\uD83D\uDFE5",title:"Red Square"},{emoji:"\uD83D\uDFE7",title:"Orange Square"},{emoji:"\uD83D\uDFE8",title:"Yellow Square"},{emoji:"\uD83D\uDFE9",title:"Green Square"},{emoji:"\uD83D\uDFE6",title:"Blue Square"},{emoji:"\uD83D\uDFEA",title:"Purple Square"},{emoji:"\uD83D\uDFEB",title:"Brown Square"},{emoji:"⬛",title:"Black Large Square"},{emoji:"⬜",title:"White Large Square"},{emoji:"◼️",title:"Black Medium Square"},{emoji:"◻️",title:"White Medium Square"},{emoji:"◾",title:"Black Medium-Small Square"},{emoji:"◽",title:"White Medium-Small Square"},{emoji:"▪️",title:"Black Small Square"},{emoji:"▫️",title:"White Small Square"},{emoji:"\uD83D\uDD36",title:"Large Orange Diamond"},{emoji:"\uD83D\uDD37",title:"Large Blue Diamond"},{emoji:"\uD83D\uDD38",title:"Small Orange Diamond"},{emoji:"\uD83D\uDD39",title:"Small Blue Diamond"},{emoji:"\uD83D\uDD3A",title:"Red Triangle Pointed Up"},{emoji:"\uD83D\uDD3B",title:"Red Triangle Pointed Down"},{emoji:"\uD83D\uDCA0",title:"Diamond with a Dot"},{emoji:"\uD83D\uDD18",title:"Radio Button"},{emoji:"\uD83D\uDD33",title:"White Square Button"},{emoji:"\uD83D\uDD32",title:"Black Square Button"}],Flags:[{emoji:"\uD83C\uDFC1",title:"Chequered Flag"},{emoji:"\uD83D\uDEA9",title:"Triangular Flag"},{emoji:"\uD83C\uDF8C",title:"Crossed Flags"},{emoji:"\uD83C\uDFF4",title:"Black Flag"},{emoji:"\uD83C\uDFF3️",title:"White Flag"},{emoji:"\uD83C\uDFF3️‍\uD83C\uDF08",title:"Rainbow Flag"},{emoji:"\uD83C\uDFF3️‍⚧️",title:"Transgender Flag"},{emoji:"\uD83C\uDFF4‍☠️",title:"Pirate Flag"},{emoji:"\uD83C\uDDE6\uD83C\uDDE8",title:"Flag: Ascension Island"},{emoji:"\uD83C\uDDE6\uD83C\uDDE9",title:"Flag: Andorra"},{emoji:"\uD83C\uDDE6\uD83C\uDDEA",title:"Flag: United Arab Emirates"},{emoji:"\uD83C\uDDE6\uD83C\uDDEB",title:"Flag: Afghanistan"},{emoji:"\uD83C\uDDE6\uD83C\uDDEC",title:"Flag: Antigua & Barbuda"},{emoji:"\uD83C\uDDE6\uD83C\uDDEE",title:"Flag: Anguilla"},{emoji:"\uD83C\uDDE6\uD83C\uDDF1",title:"Flag: Albania"},{emoji:"\uD83C\uDDE6\uD83C\uDDF2",title:"Flag: Armenia"},{emoji:"\uD83C\uDDE6\uD83C\uDDF4",title:"Flag: Angola"},{emoji:"\uD83C\uDDE6\uD83C\uDDF6",title:"Flag: Antarctica"},{emoji:"\uD83C\uDDE6\uD83C\uDDF7",title:"Flag: Argentina"},{emoji:"\uD83C\uDDE6\uD83C\uDDF8",title:"Flag: American Samoa"},{emoji:"\uD83C\uDDE6\uD83C\uDDF9",title:"Flag: Austria"},{emoji:"\uD83C\uDDE6\uD83C\uDDFA",title:"Flag: Australia"},{emoji:"\uD83C\uDDE6\uD83C\uDDFC",title:"Flag: Aruba"},{emoji:"\uD83C\uDDE6\uD83C\uDDFD",title:"Flag: \xc5land Islands"},{emoji:"\uD83C\uDDE6\uD83C\uDDFF",title:"Flag: Azerbaijan"},{emoji:"\uD83C\uDDE7\uD83C\uDDE6",title:"Flag: Bosnia & Herzegovina"},{emoji:"\uD83C\uDDE7\uD83C\uDDE7",title:"Flag: Barbados"},{emoji:"\uD83C\uDDE7\uD83C\uDDE9",title:"Flag: Bangladesh"},{emoji:"\uD83C\uDDE7\uD83C\uDDEA",title:"Flag: Belgium"},{emoji:"\uD83C\uDDE7\uD83C\uDDEB",title:"Flag: Burkina Faso"},{emoji:"\uD83C\uDDE7\uD83C\uDDEC",title:"Flag: Bulgaria"},{emoji:"\uD83C\uDDE7\uD83C\uDDED",title:"Flag: Bahrain"},{emoji:"\uD83C\uDDE7\uD83C\uDDEE",title:"Flag: Burundi"},{emoji:"\uD83C\uDDE7\uD83C\uDDEF",title:"Flag: Benin"},{emoji:"\uD83C\uDDE7\uD83C\uDDF1",title:"Flag: St. Barth\xe9lemy"},{emoji:"\uD83C\uDDE7\uD83C\uDDF2",title:"Flag: Bermuda"},{emoji:"\uD83C\uDDE7\uD83C\uDDF3",title:"Flag: Brunei"},{emoji:"\uD83C\uDDE7\uD83C\uDDF4",title:"Flag: Bolivia"},{emoji:"\uD83C\uDDE7\uD83C\uDDF6",title:"Flag: Caribbean Netherlands"},{emoji:"\uD83C\uDDE7\uD83C\uDDF7",title:"Flag: Brazil"},{emoji:"\uD83C\uDDE7\uD83C\uDDF8",title:"Flag: Bahamas"},{emoji:"\uD83C\uDDE7\uD83C\uDDF9",title:"Flag: Bhutan"},{emoji:"\uD83C\uDDE7\uD83C\uDDFB",title:"Flag: Bouvet Island"},{emoji:"\uD83C\uDDE7\uD83C\uDDFC",title:"Flag: Botswana"},{emoji:"\uD83C\uDDE7\uD83C\uDDFE",title:"Flag: Belarus"},{emoji:"\uD83C\uDDE7\uD83C\uDDFF",title:"Flag: Belize"},{emoji:"\uD83C\uDDE8\uD83C\uDDE6",title:"Flag: Canada"},{emoji:"\uD83C\uDDE8\uD83C\uDDE8",title:"Flag: Cocos (Keeling) Islands"},{emoji:"\uD83C\uDDE8\uD83C\uDDE9",title:"Flag: Congo - Kinshasa"},{emoji:"\uD83C\uDDE8\uD83C\uDDEB",title:"Flag: Central African Republic"},{emoji:"\uD83C\uDDE8\uD83C\uDDEC",title:"Flag: Congo - Brazzaville"},{emoji:"\uD83C\uDDE8\uD83C\uDDED",title:"Flag: Switzerland"},{emoji:"\uD83C\uDDE8\uD83C\uDDEE",title:"Flag: C\xf4te d’Ivoire"},{emoji:"\uD83C\uDDE8\uD83C\uDDF0",title:"Flag: Cook Islands"},{emoji:"\uD83C\uDDE8\uD83C\uDDF1",title:"Flag: Chile"},{emoji:"\uD83C\uDDE8\uD83C\uDDF2",title:"Flag: Cameroon"},{emoji:"\uD83C\uDDE8\uD83C\uDDF3",title:"Flag: China"},{emoji:"\uD83C\uDDE8\uD83C\uDDF4",title:"Flag: Colombia"},{emoji:"\uD83C\uDDE8\uD83C\uDDF5",title:"Flag: Clipperton Island"},{emoji:"\uD83C\uDDE8\uD83C\uDDF7",title:"Flag: Costa Rica"},{emoji:"\uD83C\uDDE8\uD83C\uDDFA",title:"Flag: Cuba"},{emoji:"\uD83C\uDDE8\uD83C\uDDFB",title:"Flag: Cape Verde"},{emoji:"\uD83C\uDDE8\uD83C\uDDFC",title:"Flag: Cura\xe7ao"},{emoji:"\uD83C\uDDE8\uD83C\uDDFD",title:"Flag: Christmas Island"},{emoji:"\uD83C\uDDE8\uD83C\uDDFE",title:"Flag: Cyprus"},{emoji:"\uD83C\uDDE8\uD83C\uDDFF",title:"Flag: Czechia"},{emoji:"\uD83C\uDDE9\uD83C\uDDEA",title:"Flag: Germany"},{emoji:"\uD83C\uDDE9\uD83C\uDDEC",title:"Flag: Diego Garcia"},{emoji:"\uD83C\uDDE9\uD83C\uDDEF",title:"Flag: Djibouti"},{emoji:"\uD83C\uDDE9\uD83C\uDDF0",title:"Flag: Denmark"},{emoji:"\uD83C\uDDE9\uD83C\uDDF2",title:"Flag: Dominica"},{emoji:"\uD83C\uDDE9\uD83C\uDDF4",title:"Flag: Dominican Republic"},{emoji:"\uD83C\uDDE9\uD83C\uDDFF",title:"Flag: Algeria"},{emoji:"\uD83C\uDDEA\uD83C\uDDE6",title:"Flag: Ceuta & Melilla"},{emoji:"\uD83C\uDDEA\uD83C\uDDE8",title:"Flag: Ecuador"},{emoji:"\uD83C\uDDEA\uD83C\uDDEA",title:"Flag: Estonia"},{emoji:"\uD83C\uDDEA\uD83C\uDDEC",title:"Flag: Egypt"},{emoji:"\uD83C\uDDEA\uD83C\uDDED",title:"Flag: Western Sahara"},{emoji:"\uD83C\uDDEA\uD83C\uDDF7",title:"Flag: Eritrea"},{emoji:"\uD83C\uDDEA\uD83C\uDDF8",title:"Flag: Spain"},{emoji:"\uD83C\uDDEA\uD83C\uDDF9",title:"Flag: Ethiopia"},{emoji:"\uD83C\uDDEA\uD83C\uDDFA",title:"Flag: European Union"},{emoji:"\uD83C\uDDEB\uD83C\uDDEE",title:"Flag: Finland"},{emoji:"\uD83C\uDDEB\uD83C\uDDEF",title:"Flag: Fiji"},{emoji:"\uD83C\uDDEB\uD83C\uDDF0",title:"Flag: Falkland Islands"},{emoji:"\uD83C\uDDEB\uD83C\uDDF2",title:"Flag: Micronesia"},{emoji:"\uD83C\uDDEB\uD83C\uDDF4",title:"Flag: Faroe Islands"},{emoji:"\uD83C\uDDEB\uD83C\uDDF7",title:"Flag: France"},{emoji:"\uD83C\uDDEC\uD83C\uDDE6",title:"Flag: Gabon"},{emoji:"\uD83C\uDDEC\uD83C\uDDE7",title:"Flag: United Kingdom"},{emoji:"\uD83C\uDDEC\uD83C\uDDE9",title:"Flag: Grenada"},{emoji:"\uD83C\uDDEC\uD83C\uDDEA",title:"Flag: Georgia"},{emoji:"\uD83C\uDDEC\uD83C\uDDEB",title:"Flag: French Guiana"},{emoji:"\uD83C\uDDEC\uD83C\uDDEC",title:"Flag: Guernsey"},{emoji:"\uD83C\uDDEC\uD83C\uDDED",title:"Flag: Ghana"},{emoji:"\uD83C\uDDEC\uD83C\uDDEE",title:"Flag: Gibraltar"},{emoji:"\uD83C\uDDEC\uD83C\uDDF1",title:"Flag: Greenland"},{emoji:"\uD83C\uDDEC\uD83C\uDDF2",title:"Flag: Gambia"},{emoji:"\uD83C\uDDEC\uD83C\uDDF3",title:"Flag: Guinea"},{emoji:"\uD83C\uDDEC\uD83C\uDDF5",title:"Flag: Guadeloupe"},{emoji:"\uD83C\uDDEC\uD83C\uDDF6",title:"Flag: Equatorial Guinea"},{emoji:"\uD83C\uDDEC\uD83C\uDDF7",title:"Flag: Greece"},{emoji:"\uD83C\uDDEC\uD83C\uDDF8",title:"Flag: South Georgia & South Sandwich Islands"},{emoji:"\uD83C\uDDEC\uD83C\uDDF9",title:"Flag: Guatemala"},{emoji:"\uD83C\uDDEC\uD83C\uDDFA",title:"Flag: Guam"},{emoji:"\uD83C\uDDEC\uD83C\uDDFC",title:"Flag: Guinea-Bissau"},{emoji:"\uD83C\uDDEC\uD83C\uDDFE",title:"Flag: Guyana"},{emoji:"\uD83C\uDDED\uD83C\uDDF0",title:"Flag: Hong Kong SAR China"},{emoji:"\uD83C\uDDED\uD83C\uDDF2",title:"Flag: Heard & McDonald Islands"},{emoji:"\uD83C\uDDED\uD83C\uDDF3",title:"Flag: Honduras"},{emoji:"\uD83C\uDDED\uD83C\uDDF7",title:"Flag: Croatia"},{emoji:"\uD83C\uDDED\uD83C\uDDF9",title:"Flag: Haiti"},{emoji:"\uD83C\uDDED\uD83C\uDDFA",title:"Flag: Hungary"},{emoji:"\uD83C\uDDEE\uD83C\uDDE8",title:"Flag: Canary Islands"},{emoji:"\uD83C\uDDEE\uD83C\uDDE9",title:"Flag: Indonesia"},{emoji:"\uD83C\uDDEE\uD83C\uDDEA",title:"Flag: Ireland"},{emoji:"\uD83C\uDDEE\uD83C\uDDF1",title:"Flag: Israel"},{emoji:"\uD83C\uDDEE\uD83C\uDDF2",title:"Flag: Isle of Man"},{emoji:"\uD83C\uDDEE\uD83C\uDDF3",title:"Flag: India"},{emoji:"\uD83C\uDDEE\uD83C\uDDF4",title:"Flag: British Indian Ocean Territory"},{emoji:"\uD83C\uDDEE\uD83C\uDDF6",title:"Flag: Iraq"},{emoji:"\uD83C\uDDEE\uD83C\uDDF7",title:"Flag: Iran"},{emoji:"\uD83C\uDDEE\uD83C\uDDF8",title:"Flag: Iceland"},{emoji:"\uD83C\uDDEE\uD83C\uDDF9",title:"Flag: Italy"},{emoji:"\uD83C\uDDEF\uD83C\uDDEA",title:"Flag: Jersey"},{emoji:"\uD83C\uDDEF\uD83C\uDDF2",title:"Flag: Jamaica"},{emoji:"\uD83C\uDDEF\uD83C\uDDF4",title:"Flag: Jordan"},{emoji:"\uD83C\uDDEF\uD83C\uDDF5",title:"Flag: Japan"},{emoji:"\uD83C\uDDF0\uD83C\uDDEA",title:"Flag: Kenya"},{emoji:"\uD83C\uDDF0\uD83C\uDDEC",title:"Flag: Kyrgyzstan"},{emoji:"\uD83C\uDDF0\uD83C\uDDED",title:"Flag: Cambodia"},{emoji:"\uD83C\uDDF0\uD83C\uDDEE",title:"Flag: Kiribati"},{emoji:"\uD83C\uDDF0\uD83C\uDDF2",title:"Flag: Comoros"},{emoji:"\uD83C\uDDF0\uD83C\uDDF3",title:"Flag: St. Kitts & Nevis"},{emoji:"\uD83C\uDDF0\uD83C\uDDF5",title:"Flag: North Korea"},{emoji:"\uD83C\uDDF0\uD83C\uDDF7",title:"Flag: South Korea"},{emoji:"\uD83C\uDDF0\uD83C\uDDFC",title:"Flag: Kuwait"},{emoji:"\uD83C\uDDF0\uD83C\uDDFE",title:"Flag: Cayman Islands"},{emoji:"\uD83C\uDDF0\uD83C\uDDFF",title:"Flag: Kazakhstan"},{emoji:"\uD83C\uDDF1\uD83C\uDDE6",title:"Flag: Laos"},{emoji:"\uD83C\uDDF1\uD83C\uDDE7",title:"Flag: Lebanon"},{emoji:"\uD83C\uDDF1\uD83C\uDDE8",title:"Flag: St. Lucia"},{emoji:"\uD83C\uDDF1\uD83C\uDDEE",title:"Flag: Liechtenstein"},{emoji:"\uD83C\uDDF1\uD83C\uDDF0",title:"Flag: Sri Lanka"},{emoji:"\uD83C\uDDF1\uD83C\uDDF7",title:"Flag: Liberia"},{emoji:"\uD83C\uDDF1\uD83C\uDDF8",title:"Flag: Lesotho"},{emoji:"\uD83C\uDDF1\uD83C\uDDF9",title:"Flag: Lithuania"},{emoji:"\uD83C\uDDF1\uD83C\uDDFA",title:"Flag: Luxembourg"},{emoji:"\uD83C\uDDF1\uD83C\uDDFB",title:"Flag: Latvia"},{emoji:"\uD83C\uDDF1\uD83C\uDDFE",title:"Flag: Libya"},{emoji:"\uD83C\uDDF2\uD83C\uDDE6",title:"Flag: Morocco"},{emoji:"\uD83C\uDDF2\uD83C\uDDE8",title:"Flag: Monaco"},{emoji:"\uD83C\uDDF2\uD83C\uDDE9",title:"Flag: Moldova"},{emoji:"\uD83C\uDDF2\uD83C\uDDEA",title:"Flag: Montenegro"},{emoji:"\uD83C\uDDF2\uD83C\uDDEB",title:"Flag: St. Martin"},{emoji:"\uD83C\uDDF2\uD83C\uDDEC",title:"Flag: Madagascar"},{emoji:"\uD83C\uDDF2\uD83C\uDDED",title:"Flag: Marshall Islands"},{emoji:"\uD83C\uDDF2\uD83C\uDDF0",title:"Flag: North Macedonia"},{emoji:"\uD83C\uDDF2\uD83C\uDDF1",title:"Flag: Mali"},{emoji:"\uD83C\uDDF2\uD83C\uDDF2",title:"Flag: Myanmar (Burma)"},{emoji:"\uD83C\uDDF2\uD83C\uDDF3",title:"Flag: Mongolia"},{emoji:"\uD83C\uDDF2\uD83C\uDDF4",title:"Flag: Macao Sar China"},{emoji:"\uD83C\uDDF2\uD83C\uDDF5",title:"Flag: Northern Mariana Islands"},{emoji:"\uD83C\uDDF2\uD83C\uDDF6",title:"Flag: Martinique"},{emoji:"\uD83C\uDDF2\uD83C\uDDF7",title:"Flag: Mauritania"},{emoji:"\uD83C\uDDF2\uD83C\uDDF8",title:"Flag: Montserrat"},{emoji:"\uD83C\uDDF2\uD83C\uDDF9",title:"Flag: Malta"},{emoji:"\uD83C\uDDF2\uD83C\uDDFA",title:"Flag: Mauritius"},{emoji:"\uD83C\uDDF2\uD83C\uDDFB",title:"Flag: Maldives"},{emoji:"\uD83C\uDDF2\uD83C\uDDFC",title:"Flag: Malawi"},{emoji:"\uD83C\uDDF2\uD83C\uDDFD",title:"Flag: Mexico"},{emoji:"\uD83C\uDDF2\uD83C\uDDFE",title:"Flag: Malaysia"},{emoji:"\uD83C\uDDF2\uD83C\uDDFF",title:"Flag: Mozambique"},{emoji:"\uD83C\uDDF3\uD83C\uDDE6",title:"Flag: Namibia"},{emoji:"\uD83C\uDDF3\uD83C\uDDE8",title:"Flag: New Caledonia"},{emoji:"\uD83C\uDDF3\uD83C\uDDEA",title:"Flag: Niger"},{emoji:"\uD83C\uDDF3\uD83C\uDDEB",title:"Flag: Norfolk Island"},{emoji:"\uD83C\uDDF3\uD83C\uDDEC",title:"Flag: Nigeria"},{emoji:"\uD83C\uDDF3\uD83C\uDDEE",title:"Flag: Nicaragua"},{emoji:"\uD83C\uDDF3\uD83C\uDDF1",title:"Flag: Netherlands"},{emoji:"\uD83C\uDDF3\uD83C\uDDF4",title:"Flag: Norway"},{emoji:"\uD83C\uDDF3\uD83C\uDDF5",title:"Flag: Nepal"},{emoji:"\uD83C\uDDF3\uD83C\uDDF7",title:"Flag: Nauru"},{emoji:"\uD83C\uDDF3\uD83C\uDDFA",title:"Flag: Niue"},{emoji:"\uD83C\uDDF3\uD83C\uDDFF",title:"Flag: New Zealand"},{emoji:"\uD83C\uDDF4\uD83C\uDDF2",title:"Flag: Oman"},{emoji:"\uD83C\uDDF5\uD83C\uDDE6",title:"Flag: Panama"},{emoji:"\uD83C\uDDF5\uD83C\uDDEA",title:"Flag: Peru"},{emoji:"\uD83C\uDDF5\uD83C\uDDEB",title:"Flag: French Polynesia"},{emoji:"\uD83C\uDDF5\uD83C\uDDEC",title:"Flag: Papua New Guinea"},{emoji:"\uD83C\uDDF5\uD83C\uDDED",title:"Flag: Philippines"},{emoji:"\uD83C\uDDF5\uD83C\uDDF0",title:"Flag: Pakistan"},{emoji:"\uD83C\uDDF5\uD83C\uDDF1",title:"Flag: Poland"},{emoji:"\uD83C\uDDF5\uD83C\uDDF2",title:"Flag: St. Pierre & Miquelon"},{emoji:"\uD83C\uDDF5\uD83C\uDDF3",title:"Flag: Pitcairn Islands"},{emoji:"\uD83C\uDDF5\uD83C\uDDF7",title:"Flag: Puerto Rico"},{emoji:"\uD83C\uDDF5\uD83C\uDDF8",title:"Flag: Palestinian Territories"},{emoji:"\uD83C\uDDF5\uD83C\uDDF9",title:"Flag: Portugal"},{emoji:"\uD83C\uDDF5\uD83C\uDDFC",title:"Flag: Palau"},{emoji:"\uD83C\uDDF5\uD83C\uDDFE",title:"Flag: Paraguay"},{emoji:"\uD83C\uDDF6\uD83C\uDDE6",title:"Flag: Qatar"},{emoji:"\uD83C\uDDF7\uD83C\uDDEA",title:"Flag: R\xe9union"},{emoji:"\uD83C\uDDF7\uD83C\uDDF4",title:"Flag: Romania"},{emoji:"\uD83C\uDDF7\uD83C\uDDF8",title:"Flag: Serbia"},{emoji:"\uD83C\uDDF7\uD83C\uDDFA",title:"Flag: Russia"},{emoji:"\uD83C\uDDF7\uD83C\uDDFC",title:"Flag: Rwanda"},{emoji:"\uD83C\uDDF8\uD83C\uDDE6",title:"Flag: Saudi Arabia"},{emoji:"\uD83C\uDDF8\uD83C\uDDE7",title:"Flag: Solomon Islands"},{emoji:"\uD83C\uDDF8\uD83C\uDDE8",title:"Flag: Seychelles"},{emoji:"\uD83C\uDDF8\uD83C\uDDE9",title:"Flag: Sudan"},{emoji:"\uD83C\uDDF8\uD83C\uDDEA",title:"Flag: Sweden"},{emoji:"\uD83C\uDDF8\uD83C\uDDEC",title:"Flag: Singapore"},{emoji:"\uD83C\uDDF8\uD83C\uDDED",title:"Flag: St. Helena"},{emoji:"\uD83C\uDDF8\uD83C\uDDEE",title:"Flag: Slovenia"},{emoji:"\uD83C\uDDF8\uD83C\uDDEF",title:"Flag: Svalbard & Jan Mayen"},{emoji:"\uD83C\uDDF8\uD83C\uDDF0",title:"Flag: Slovakia"},{emoji:"\uD83C\uDDF8\uD83C\uDDF1",title:"Flag: Sierra Leone"},{emoji:"\uD83C\uDDF8\uD83C\uDDF2",title:"Flag: San Marino"},{emoji:"\uD83C\uDDF8\uD83C\uDDF3",title:"Flag: Senegal"},{emoji:"\uD83C\uDDF8\uD83C\uDDF4",title:"Flag: Somalia"},{emoji:"\uD83C\uDDF8\uD83C\uDDF7",title:"Flag: Suriname"},{emoji:"\uD83C\uDDF8\uD83C\uDDF8",title:"Flag: South Sudan"},{emoji:"\uD83C\uDDF8\uD83C\uDDF9",title:"Flag: S\xe3o Tom\xe9 & Pr\xedncipe"},{emoji:"\uD83C\uDDF8\uD83C\uDDFB",title:"Flag: El Salvador"},{emoji:"\uD83C\uDDF8\uD83C\uDDFD",title:"Flag: Sint Maarten"},{emoji:"\uD83C\uDDF8\uD83C\uDDFE",title:"Flag: Syria"},{emoji:"\uD83C\uDDF8\uD83C\uDDFF",title:"Flag: Eswatini"},{emoji:"\uD83C\uDDF9\uD83C\uDDE6",title:"Flag: Tristan Da Cunha"},{emoji:"\uD83C\uDDF9\uD83C\uDDE8",title:"Flag: Turks & Caicos Islands"},{emoji:"\uD83C\uDDF9\uD83C\uDDE9",title:"Flag: Chad"},{emoji:"\uD83C\uDDF9\uD83C\uDDEB",title:"Flag: French Southern Territories"},{emoji:"\uD83C\uDDF9\uD83C\uDDEC",title:"Flag: Togo"},{emoji:"\uD83C\uDDF9\uD83C\uDDED",title:"Flag: Thailand"},{emoji:"\uD83C\uDDF9\uD83C\uDDEF",title:"Flag: Tajikistan"},{emoji:"\uD83C\uDDF9\uD83C\uDDF0",title:"Flag: Tokelau"},{emoji:"\uD83C\uDDF9\uD83C\uDDF1",title:"Flag: Timor-Leste"},{emoji:"\uD83C\uDDF9\uD83C\uDDF2",title:"Flag: Turkmenistan"},{emoji:"\uD83C\uDDF9\uD83C\uDDF3",title:"Flag: Tunisia"},{emoji:"\uD83C\uDDF9\uD83C\uDDF4",title:"Flag: Tonga"},{emoji:"\uD83C\uDDF9\uD83C\uDDF7",title:"Flag: Turkey"},{emoji:"\uD83C\uDDF9\uD83C\uDDF9",title:"Flag: Trinidad & Tobago"},{emoji:"\uD83C\uDDF9\uD83C\uDDFB",title:"Flag: Tuvalu"},{emoji:"\uD83C\uDDF9\uD83C\uDDFC",title:"Flag: Taiwan"},{emoji:"\uD83C\uDDF9\uD83C\uDDFF",title:"Flag: Tanzania"},{emoji:"\uD83C\uDDFA\uD83C\uDDE6",title:"Flag: Ukraine"},{emoji:"\uD83C\uDDFA\uD83C\uDDEC",title:"Flag: Uganda"},{emoji:"\uD83C\uDDFA\uD83C\uDDF2",title:"Flag: U.S. Outlying Islands"},{emoji:"\uD83C\uDDFA\uD83C\uDDF3",title:"Flag: United Nations"},{emoji:"\uD83C\uDDFA\uD83C\uDDF8",title:"Flag: United States"},{emoji:"\uD83C\uDDFA\uD83C\uDDFE",title:"Flag: Uruguay"},{emoji:"\uD83C\uDDFA\uD83C\uDDFF",title:"Flag: Uzbekistan"},{emoji:"\uD83C\uDDFB\uD83C\uDDE6",title:"Flag: Vatican City"},{emoji:"\uD83C\uDDFB\uD83C\uDDE8",title:"Flag: St. Vincent & Grenadines"},{emoji:"\uD83C\uDDFB\uD83C\uDDEA",title:"Flag: Venezuela"},{emoji:"\uD83C\uDDFB\uD83C\uDDEC",title:"Flag: British Virgin Islands"},{emoji:"\uD83C\uDDFB\uD83C\uDDEE",title:"Flag: U.S. Virgin Islands"},{emoji:"\uD83C\uDDFB\uD83C\uDDF3",title:"Flag: Vietnam"},{emoji:"\uD83C\uDDFB\uD83C\uDDFA",title:"Flag: Vanuatu"},{emoji:"\uD83C\uDDFC\uD83C\uDDEB",title:"Flag: Wallis & Futuna"},{emoji:"\uD83C\uDDFC\uD83C\uDDF8",title:"Flag: Samoa"},{emoji:"\uD83C\uDDFD\uD83C\uDDF0",title:"Flag: Kosovo"},{emoji:"\uD83C\uDDFE\uD83C\uDDEA",title:"Flag: Yemen"},{emoji:"\uD83C\uDDFE\uD83C\uDDF9",title:"Flag: Mayotte"},{emoji:"\uD83C\uDDFF\uD83C\uDDE6",title:"Flag: South Africa"},{emoji:"\uD83C\uDDFF\uD83C\uDDF2",title:"Flag: Zambia"},{emoji:"\uD83C\uDDFF\uD83C\uDDFC",title:"Flag: Zimbabwe"},{emoji:"\uD83C\uDFF4\uDB40\uDC67\uDB40\uDC62\uDB40\uDC65\uDB40\uDC6E\uDB40\uDC67\uDB40\uDC7F",title:"Flag: England"},{emoji:"\uD83C\uDFF4\uDB40\uDC67\uDB40\uDC62\uDB40\uDC73\uDB40\uDC63\uDB40\uDC74\uDB40\uDC7F",title:"Flag: Scotland"},{emoji:"\uD83C\uDFF4\uDB40\uDC67\uDB40\uDC62\uDB40\uDC77\uDB40\uDC6C\uDB40\uDC73\uDB40\uDC7F",title:"Flag: Wales"},{emoji:"\uD83C\uDFF4\uDB40\uDC75\uDB40\uDC73\uDB40\uDC74\uDB40\uDC78\uDB40\uDC7F",title:"Flag for Texas (US-TX)"}]},n={People:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"/> </g> </g> <g> <g> <path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368 c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99 c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166 c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z"/> </g> </g> <g> <g> <circle cx="168" cy="180.12" r="32"/> </g> </g> <g> <g> <circle cx="344" cy="180.12" r="32"/> </g> </g> <g> </g> <g> </g> <g> </g> </svg>',Nature:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 354.968 354.968" style="enable-background:new 0 0 354.968 354.968;" xml:space="preserve"> <g> <g> <path d="M350.775,341.319c-9.6-28.4-20.8-55.2-34.4-80.8c0.4-0.4,0.8-1.2,1.6-1.6c30.8-34.8,44-83.6,20.4-131.6 c-20.4-41.6-65.6-76.4-124.8-98.8c-57.2-22-127.6-32.4-200.4-27.2c-5.6,0.4-10,5.2-9.6,10.8c0.4,2.8,1.6,5.6,4,7.2 c36.8,31.6,50,79.2,63.6,126.8c8,28,15.6,55.6,28.4,81.2c0,0.4,0.4,0.4,0.4,0.8c30.8,59.6,78,81.2,122.8,78.4 c18.4-1.2,36-6.4,52.4-14.4c9.2-4.8,18-10.4,26-16.8c11.6,23.2,22,47.2,30.4,72.8c1.6,5.2,7.6,8,12.8,6.4 C349.975,352.119,352.775,346.519,350.775,341.319z M271.175,189.319c-34.8-44.4-78-82.4-131.6-112.4c-4.8-2.8-11.2-1.2-13.6,4 c-2.8,4.8-1.2,11.2,4,13.6c50.8,28.8,92.4,64.8,125.6,107.2c13.2,17.2,25.2,35.2,36,54c-8,7.6-16.4,13.6-25.6,18 c-14,7.2-28.8,11.6-44.4,12.4c-37.6,2.4-77.2-16-104-67.6v-0.4c-11.6-24-19.2-50.8-26.8-78c-12.4-43.2-24.4-86.4-53.6-120.4 c61.6-1.6,120.4,8.4,169.2,27.2c54.4,20.8,96,52,114,88.8c18.8,38,9.2,76.8-14.4,105.2 C295.575,222.919,283.975,205.719,271.175,189.319z"/> </g> </g> <g> </g> <g> </g> <g> </g> </svg>',"Food-dring":'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 295 295" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 295 295"> <g> <path d="M25,226.011v16.511c0,8.836,7.465,16.489,16.302,16.489h214.063c8.837,0,15.636-7.653,15.636-16.489v-16.511H25z"/> <path d="m271.83,153.011c-3.635-66-57.634-117.022-123.496-117.022-65.863,0-119.863,51.021-123.498,117.022h246.994zm-198.497-50.99c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm42,33c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm33.248-58c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm32.752,58c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm50.25-41.25c0,4.557-3.693,8.25-8.25,8.25-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.694 8.25,8.25z"/> <path d="m275.414,169.011h-0.081-254.825c-11.142,0-20.508,8.778-20.508,19.921v0.414c0,11.143 9.366,20.665 20.508,20.665h254.906c11.142,0 19.586-9.523 19.586-20.665v-0.414c0-11.143-8.444-19.921-19.586-19.921z"/> </g> </svg>',Activity:'<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path id="XMLID_272_" d="m437.02 74.98c-48.353-48.351-112.64-74.98-181.02-74.98s-132.667 26.629-181.02 74.98c-48.351 48.353-74.98 112.64-74.98 181.02s26.629 132.667 74.98 181.02c48.353 48.351 112.64 74.98 181.02 74.98s132.667-26.629 181.02-74.98c48.351-48.353 74.98-112.64 74.98-181.02s-26.629-132.667-74.98-181.02zm-407.02 181.02c0-57.102 21.297-109.316 56.352-149.142 37.143 45.142 57.438 101.499 57.438 160.409 0 53.21-16.914 105.191-47.908 148.069-40.693-40.891-65.882-97.226-65.882-159.336zm88.491 179.221c35.75-48.412 55.3-107.471 55.3-167.954 0-66.866-23.372-130.794-66.092-181.661 39.718-34.614 91.603-55.606 148.301-55.606 56.585 0 108.376 20.906 148.064 55.396-42.834 50.9-66.269 114.902-66.269 181.872 0 60.556 19.605 119.711 55.448 168.158-38.077 29.193-85.665 46.574-137.243 46.574-51.698 0-99.388-17.461-137.509-46.779zm297.392-19.645c-31.104-42.922-48.088-95.008-48.088-148.309 0-59.026 20.367-115.47 57.638-160.651 35.182 39.857 56.567 92.166 56.567 149.384 0 62.23-25.284 118.665-66.117 159.576z"/></svg>',"Travel-places":'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"> <g><g><path d="M846.5,153.5C939,246.1,990,369.1,990,500c0,130.9-51,253.9-143.5,346.5C753.9,939,630.9,990,500,990c-130.9,0-253.9-51-346.5-143.5C61,753.9,10,630.9,10,500c0-130.9,51-253.9,143.5-346.5C246.1,61,369.1,10,500,10C630.9,10,753.9,61,846.5,153.5z M803.2,803.2c60.3-60.3,100.5-135.5,117-217.3c-12.9,19-25.2,26-32.9-16.5c-7.9-69.3-71.5-25-111.5-49.6c-42.1,28.4-136.8-55.2-120.7,39.1c24.8,42.5,134-56.9,79.6,33.1c-34.7,62.8-126.9,201.9-114.9,274c1.5,105-107.3,21.9-144.8-12.9c-25.2-69.8-8.6-191.8-74.6-225.9c-71.6-3.1-133-9.6-160.8-89.6c-16.7-57.3,17.8-142.5,79.1-155.7c89.8-56.4,121.9,66.1,206.1,68.4c26.2-27.4,97.4-36.1,103.4-66.8c-55.3-9.8,70.1-46.5-5.3-67.4c-41.6,4.9-68.4,43.1-46.3,75.6C496,410.3,493.5,274.8,416,317.6c-2,67.6-126.5,21.9-43.1,8.2c28.7-12.5-46.8-48.8-6-42.2c20-1.1,87.4-24.7,69.2-40.6c37.5-23.3,69.1,55.8,105.8-1.8c26.5-44.3-11.1-52.5-44.4-30c-18.7-21,33.1-66.3,78.8-85.9c15.2-6.5,29.8-10.1,40.9-9.1c23,26.6,65.6,31.2,67.8-3.2c-57-27.3-119.9-41.7-185-41.7c-93.4,0-182.3,29.7-255.8,84.6c19.8,9.1,31,20.3,11.9,34.7c-14.8,44.1-74.8,103.2-127.5,94.9c-27.4,47.2-45.4,99.2-53.1,153.6c44.1,14.6,54.3,43.5,44.8,53.2c-22.5,19.6-36.3,47.4-43.4,77.8C91.3,658,132.6,739,196.8,803.2c81,81,188.6,125.6,303.2,125.6C614.5,928.8,722.2,884.2,803.2,803.2z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></g> </svg>',Objects:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 461.977 461.977" style="enable-background:new 0 0 461.977 461.977;" xml:space="preserve"> <g> <path d="M398.47,248.268L346.376,18.543C344.136,8.665,333.287,0,323.158,0H138.821c-10.129,0-20.979,8.665-23.219,18.543 L63.507,248.268c-0.902,3.979-0.271,7.582,1.775,10.145c2.047,2.564,5.421,3.975,9.501,3.975h51.822v39.108 c-6.551,3.555-11,10.493-11,18.47c0,11.598,9.402,21,21,21c11.598,0,21-9.402,21-21c0-7.978-4.449-14.916-11-18.47v-39.108h240.587 c4.079,0,7.454-1.412,9.501-3.975C398.742,255.849,399.372,252.247,398.47,248.268z"/> <path d="M318.735,441.977h-77.747V282.388h-20v159.588h-77.747c-5.523,0-10,4.477-10,10c0,5.523,4.477,10,10,10h175.494 c5.522,0,10-4.477,10-10C328.735,446.454,324.257,441.977,318.735,441.977z"/> </g> <g> </g> <g> </g> <g> </g> </svg>',Symbols:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.487 30.486" style="enable-background:new 0 0 30.487 30.486;" xml:space="preserve"> <g> <path d="M28.866,17.477h-2.521V15.03h-2.56c0.005-2.8-0.304-5.204-0.315-5.308l-0.088-0.67L22.75,8.811 c-0.021-0.008-0.142-0.051-0.317-0.109l2.287-8.519L19,4.836L15.23,0.022V0l-0.009,0.01L15.215,0v0.021l-3.769,4.815L5.725,0.183 l2.299,8.561c-0.157,0.051-0.268,0.09-0.288,0.098L7.104,9.084l-0.088,0.67c-0.013,0.104-0.321,2.508-0.316,5.308h-2.56v2.446H1.62 l0.447,2.514L1.62,22.689h6.474c1.907,2.966,5.186,7.549,7.162,7.797v-0.037c1.979-0.283,5.237-4.838,7.137-7.79h6.474l-0.447-2.67 L28.866,17.477z M21.137,20.355c-0.422,1.375-4.346,6.949-5.907,7.758v0.015c-1.577-0.853-5.461-6.373-5.882-7.739 c-0.002-0.043-0.005-0.095-0.008-0.146l11.804-0.031C21.141,20.264,21.139,20.314,21.137,20.355z M8.972,15.062 c-0.003-1.769,0.129-3.403,0.219-4.298c0.98-0.271,3.072-0.723,6.065-0.78v-0.03c2.979,0.06,5.063,0.51,6.04,0.779 c0.09,0.895,0.223,2.529,0.219,4.298L8.972,15.062z"/> </g> <g> </g> <g> </g> <g> </g> </svg>',Flags:'<svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill-rule="evenodd"><g id="037---Waypoint-Flag" fill-rule="nonzero" transform="translate(0 -1)"><path id="Shape" d="m59.0752 28.5054c-3.7664123-1.873859-7.2507049-4.2678838-10.3506-7.1118 1.5923634-6.0211307 2.7737841-12.14349669 3.5361-18.3248.1788-1.44-.623-1.9047-.872-2.0126-.7016942-.26712004-1.4944908-.00419148-1.8975.6293-5.4726 6.5479-12.9687 5.8008-20.9053 5.0054-7.9985-.8-16.2506-1.6116-22.3684 5.4114-.85552122-1.067885-2.26533581-1.5228479-3.5837-1.1565l-.1377.0386c-1.81412367.5095218-2.87378593 2.391025-2.3691 4.2065l12.2089 43.6891c.3541969 1.2645215 1.5052141 2.1399137 2.8184 2.1435.2677318-.0003961.5341685-.0371657.792-.1093l1.0683-.2984h.001c.7485787-.2091577 1.3833789-.7071796 1.7646969-1.3844635.381318-.677284.4779045-1.478326.2685031-2.2268365l-3.7812-13.5327c5.5066-7.0807 13.18-6.3309 21.2988-5.52 8.1094.81 16.4863 1.646 22.64-5.7129l.0029-.0039c.6044387-.7534187.8533533-1.7315007.6826-2.6822-.0899994-.4592259-.3932698-.8481635-.8167-1.0474zm-42.0381 29.7446c-.1201754.2157725-.3219209.3742868-.56.44l-1.0684.2983c-.4949157.1376357-1.0078362-.1513714-1.1465-.646l-12.2095-43.6895c-.20840349-.7523825.23089143-1.5316224.9825-1.7428l.1367-.0381c.12366014-.0348192.25153137-.0524183.38-.0523.63429117.0010181 1.19083557.4229483 1.3631 1.0334l.1083.3876v.0021l6.2529 22.3755 5.8468 20.9238c.0669515.2380103.0360256.4929057-.0859.708zm40.6329-27.2925c-5.4736 6.5459-12.9707 5.7974-20.9043 5.0039-7.9033-.79-16.06-1.605-22.1552 5.1558l-5.463-19.548-2.0643-7.3873c5.5068-7.0794 13.1796-6.3119 21.3045-5.5007 7.7148.7695 15.6787 1.5664 21.7373-4.7095-.7467138 5.70010904-1.859683 11.3462228-3.332 16.9033-.1993066.7185155.0267229 1.4878686.583 1.9844 3.1786296 2.9100325 6.7366511 5.3762694 10.5771 7.3315-.0213812.2768572-.1194065.5422977-.2831.7666z"/></g></g></svg>'},j={search:'<svg style="fill: #646772;" version="1.1" width="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 487.95 487.95" style="enable-background:new 0 0 487.95 487.95;" xml:space="preserve"> <g> <g> <path d="M481.8,453l-140-140.1c27.6-33.1,44.2-75.4,44.2-121.6C386,85.9,299.5,0.2,193.1,0.2S0,86,0,191.4s86.5,191.1,192.9,191.1 c45.2,0,86.8-15.5,119.8-41.4l140.5,140.5c8.2,8.2,20.4,8.2,28.6,0C490,473.4,490,461.2,481.8,453z M41,191.4 c0-82.8,68.2-150.1,151.9-150.1s151.9,67.3,151.9,150.1s-68.2,150.1-151.9,150.1S41,274.1,41,191.4z"/> </g> </g> <g> </g> <g> </g> </svg>',close:'<svg style="height: 11px !important;" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg"><path d="M28.94,26,51.39,3.55A2.08,2.08,0,0,0,48.45.61L26,23.06,3.55.61A2.08,2.08,0,0,0,.61,3.55L23.06,26,.61,48.45A2.08,2.08,0,0,0,2.08,52a2.05,2.05,0,0,0,1.47-.61L26,28.94,48.45,51.39a2.08,2.08,0,0,0,2.94-2.94Z"/></svg>',move:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.006 512.006" xml:space="preserve"> <g> <g> <path d="M508.247,246.756l-72.457-72.465c-5.009-5.009-13.107-5.009-18.116,0c-5.009,5.009-5.009,13.107,0,18.116l50.594,50.594 H268.811V43.748l50.594,50.594c5.009,5.009,13.107,5.009,18.116,0c5.009-5.009,5.009-13.107,0-18.116L265.056,3.761 c-5.001-5.009-13.107-5.009-18.116,0l-72.457,72.457c-5.009,5.009-5.009,13.107,0,18.116c5.001,5.009,13.107,5.009,18.116,0 l50.594-50.594v199.27H43.744l50.594-50.594c5.009-5.009,5.009-13.107,0-18.116c-5.009-5.009-13.107-5.009-18.116,0L3.757,246.756 c-5.009,5.001-5.009,13.107,0,18.116l72.465,72.457c5.009,5.009,13.107,5.009,18.116,0c5.009-5.001,5.009-13.107,0-18.116 l-50.594-50.594h199.458v199.646l-50.594-50.594c-5.009-5.001-13.107-5.001-18.116,0c-5.009,5.009-5.009,13.107,0,18.116 l72.457,72.465c5,5,13.107,5,18.116,0l72.465-72.457c5.009-5.009,5.009-13.107,0-18.116c-5.009-5-13.107-5-18.116,0 l-50.594,50.594V268.627h199.458l-50.594,50.594c-5.009,5.009-5.009,13.107,0,18.116s13.107,5.009,18.116,0l72.465-72.457 C513.257,259.872,513.257,251.765,508.247,246.756z"/> </g> </g> <g> </g> </svg>'},r={styles:()=>{let e=`
                <style>
                    .fg-emoji-container {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: ${a}px;
                        height: 400px;
                        border-radius: 5px;
                        box-shadow: 0px 3px 20px 0px rgba(0, 0, 0, 0.62);
                        background-color: white;
                        overflow: hidden;
                        z-index: 9999;
                    }

                    .fg-emoji-container svg {
                        max-width: 100%;
                        box-sizing: border-box;
                        width: 15px;
                        height: 15px;
                    }

                    .fg-emoji-picker-category-title {
                        display: block;
                        margin: 20px 0 0 0;
                        padding: 0 10px 5px 10px;
                        font-size: 16px;
                        font-family: sans-serif;
                        font-weight: bold;
                        flex: 0 0 calc(100% - 20px);
                        border-bottom: 1px solid #ededed;
                    }

                    .fg-emoji-nav {
                        background-color: #646772;
                    }

                    .fg-emoji-nav li a svg {
                        transition: all .2s ease;
                        fill: white;
                    }

                    .fg-emoji-nav li:hover a svg {
                        fill: black;
                    }

                    .fg-emoji-nav ul {
                        display: flex;
                        flex-wrap: wrap;
                        list-style: none;
                        margin: 0;
                        padding: 0;
                        border-bottom: 1px solid #dbdbdb;
                    }

                    .fg-emoji-nav ul li {
                        flex: 1;
                    }

                    .fg-emoji-nav ul li a {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 40px;
                        transition: all .2s ease;
                    }

                    .fg-emoji-nav ul li a:hover {
                        background-color: #e9ebf1;
                    }

                    .fg-emoji-nav ul li.active a {
                        background-color: #e9ebf1;
                    }

                    .fg-emoji-nav ul li.emoji-picker-nav-active a {
                        background-color: #e9ebf1;
                    }

                    .fg-emoji-nav ul li.emoji-picker-nav-active a svg {
                        fill: #646772;
                    }

                    .fg-emoji-picker-move {
                        /* pointer-events: none; */
                        cursor: move;
                    }

                    .fg-picker-special-buttons a {
                        background-color: ${this.options.specialButtons?this.options.specialButtons:"#ed5e28"};
                    }

                    .fg-picker-special-buttons:last-child a {
                        box-shadow: inset 1px 0px 0px 0 rgba(0, 0, 0, 0.11);
                    }

                    .fg-emoji-list {
                        list-style: none;
                        margin: 0;
                        padding: 0;
                        overflow-y: scroll;
                        overflow-x: hidden;
                        height: 323px;
                    }

                    .fg-emoji-picker-category-wrapper {
                        display: flex;
                        flex-wrap: wrap;
                        flex: 1;
                    }

                    .fg-emoji-list li {
                        position: relative;
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        align-items: center;
                        flex: 0 0 calc(100% / 6);
                        height: 50px;
                    }

                    .fg-emoji-list li a {
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        text-decoration: none;
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        align-items: center;
                        font-size: 23px;
                        background-color: #ffffff;
                        border-radius: 3px;
                        transition: all .3s ease;
                    }
                    
                    .fg-emoji-list li a:hover {
                        background-color: #ebebeb;
                    }

                    .fg-emoji-picker-search {
                        position: relative;
                    }

                    .fg-emoji-picker-search input {
                        border: none;
                        box-shadow: 0 0 0 0;
                        outline: none;
                        width: calc(100% - 30px);
                        display: block;
                        padding: 10px 15px;
                        background-color: #f3f3f3;
                    }

                    .fg-emoji-picker-search .fg-emoji-picker-search-icon {
                        position: absolute;
                        right: 0;
                        top: 0;
                        width: 40px;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                </style>
            `;document.head.insertAdjacentHTML("beforeend",e)},position(){let e=window.event,i=e.clientX,t=e.clientY,o={};return o.left=i,o.top=t,o},rePositioning(e){e.getBoundingClientRect().right>window.screen.availWidth&&(e.style.left=window.screen.availWidth-e.offsetWidth+"px"),window.innerHeight>400&&e.getBoundingClientRect().bottom>window.innerHeight&&(e.style.top=window.innerHeight-e.offsetHeight+"px")},render:(e,l)=>{o=void 0;let a=this.options.trigger.findIndex(e=>e.selector===l);this.insertInto=this.options.trigger[a].insertInto;let g=r.position();if(!i.length){for(let s in m)if(m.hasOwnProperty.call(m,s)){let _=m[s];t+=`<li>
                            <a title="${s}" href="#${s}">${n[s]}</a>
                        </li>`,i+=`<div class="fg-emoji-picker-category-wrapper" id="${s}">`,i+=`<p class="fg-emoji-picker-category-title">${s}</p>`,_.forEach(e=>{i+=`<li data-title="${e.title.toLowerCase()}">
                                    <a title="${e.title}" href="#">${e.emoji}</a>
                                </li>`}),i+="</div>"}}document.querySelector(".fg-emoji-container")&&this.lib(".fg-emoji-container").remove();let c=`
                <div class="fg-emoji-container" style="left: ${g.left}px; top: ${g.top}px;">
                    <nav class="fg-emoji-nav">
                        <ul>
                            ${t}

                            <li class="fg-picker-special-buttons" id="fg-emoji-picker-move"><a class="fg-emoji-picker-move" href="#">${j.move}</a></li>
                            ${this.options.closeButton?'<li class="fg-picker-special-buttons"><a id="fg-emoji-picker-close-button" href="#">'+j.close+"</a></li>":""}
                        </ul>
                    </nav>

                    <div class="fg-emoji-picker-search">
                        <input type="text" placeholder="Search" autofocus />
                        
                        <span class="fg-emoji-picker-search-icon">${j.search}</sapn>
                    </div>

                    <div>
                        <!--<div class="fg-emoji-picker-loader-animation">
                            <div class="spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>-->

                        <ul class="fg-emoji-list">
                            ${i}
                        </ul>
                    </div>
                </div>
            `;document.body.insertAdjacentHTML("beforeend",c),r.rePositioning(document.querySelector(".fg-emoji-container")),setTimeout(()=>{document.querySelector(".fg-emoji-picker-search input").focus()},500)},closePicker:e=>{e.preventDefault(),this.lib(".fg-emoji-container").remove(),l=!1},checkPickerExist(e){!document.querySelector(".fg-emoji-container")||e.target.closest(".fg-emoji-container")||l||r.closePicker.call(this,e)},setCaretPosition(e,i){var t=e;if(null!=t){if(t.createTextRange){var o=t.createTextRange();o.move("character",i),o.select()}else t.selectionStart?(t.focus(),t.setSelectionRange(i,i)):t.focus()}},insert:e=>{e.preventDefault();let i=e.target.innerText.trim(),t=document.querySelectorAll(this.insertInto),o=i;t.forEach(i=>{if(document.selection)i.focus(),(sel=document.selection.createRange()).text=o;else if(i.selectionStart||"0"==i.selectionStart){let t=i.selectionStart,l=i.selectionEnd;i.value=i.value.substring(0,t)+o+i.value.substring(l,i.value.length),r.setCaretPosition(i,t+2)}else i.value+=o,i.focus();i.dispatchEvent(new InputEvent("input")),this.options.closeOnSelect&&r.closePicker.call(this,e)})},categoryNav:e=>{e.preventDefault();let i=e.target.closest("a");if(i.getAttribute("id")&&"fg-emoji-picker-close-button"===i.getAttribute("id")||i.className.includes("fg-emoji-picker-move"))return!1;let t=i.getAttribute("href"),o=document.querySelector(".fg-emoji-list"),l=o.querySelector(`${t}`);this.lib(".fg-emoji-nav li").removeClass("emoji-picker-nav-active"),i.closest("li").classList.add("emoji-picker-nav-active"),l.scrollIntoView({behavior:"smooth",block:"start",inline:"nearest"})},search(e){let i=e.target.value.trim();o||(o=Array.from(document.querySelectorAll(".fg-emoji-picker-category-wrapper li"))),o.filter(e=>{e.getAttribute("data-title").match(i)?e.style.display="":e.style.display="none"})},mouseDown(e){e.preventDefault(),l=!0},mouseUp(e){e.preventDefault(),l=!1},mouseMove(e){if(l){e.preventDefault();let i=document.querySelector(".fg-emoji-container");i.style.left=e.clientX-320+"px",i.style.top=e.clientY-10+"px"}}},g=()=>{this.lib(document.body).on("click",r.closePicker,"#fg-emoji-picker-close-button"),this.lib(document.body).on("click",r.checkPickerExist),this.lib(document.body).on("click",r.render,this.trigger),this.lib(document.body).on("click",r.insert,".fg-emoji-list a"),this.lib(document.body).on("click",r.categoryNav,".fg-emoji-nav a"),this.lib(document.body).on("input",r.search,".fg-emoji-picker-search input"),this.lib(document).on("mousedown",r.mouseDown,"#fg-emoji-picker-move"),this.lib(document).on("mouseup",r.mouseUp,"#fg-emoji-picker-move"),this.lib(document).on("mousemove",r.mouseMove)};(()=>{r.styles(),g.call(this)})()};





//Define some global variables:
var has_unsaved_changes = false; //Tracks source/idea modal edits



//Full Story
if(js_pl_id > 1 && js_e___30849[website_id]['m__message'].length>1){ //Any user other than Shervin

    console.log('Activated Recording for Org '+js_e___30849[website_id]['m__message'])
    window['_fs_debug'] = false;
    window['_fs_host'] = 'fullstory.com';
    window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
    window['_fs_org'] = js_e___30849[website_id]['m__message'];
    window['_fs_namespace'] = 'FS';
    (function(m,n,e,t,l,o,g,y){
        if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
        g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
        o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
        y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
        g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
        g.anonymize=function(){g.identify(!!0)};
        g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
        g.log = function(a,b){g("log",[a,b])};
        g.consent=function(a){g("consent",!arguments.length||a)};
        g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
        g.clearUserCookie=function(){};
        g._w={};y='XMLHttpRequest';g._w[y]=m[y];y='fetch';g._w[y]=m[y];
        if(m[y])m[y]=function(){return g._w[y].apply(this,arguments)};
        g._v="1.2.0";
    })(window,document,window['_fs_namespace'],'script','user');

    if(js_pl_id>0){
        //https://help.fullstory.com/hc/en-us/articles/360020623294-FS-setUserVars-Recording-custom-user-data
        FS.identify(js_pl_id, {
            displayName: js_pl_name,
            uid: js_pl_id,
            profileURL: base_url+'/@'+js_pl_handle
        });
    }


}


jQuery.fn.sortElements = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing it's original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();

function htmlentitiesjs(rawStr){
    return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
        return '&#'+i.charCodeAt(0)+';';
    });
}


function mass_apply_preview(apply_id, s__id){

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="s__id"]').val(s__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .mass_apply_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...');
    $.post("/x/mass_apply_preview", {
        apply_id: apply_id,
        s__id: s__id
    }, function (data) {
        $('#modal'+apply_id+' .mass_apply_preview').html(data);
    });

}


function load_editor(){

    $('.mass_action_toggle').change(function () {
        $('.mass_action_item').addClass('hidden');
        $('.mass_id_' + $(this).val() ).removeClass('hidden');
    });

    if(parseInt(js_e___6404[12678]['m__message'])){

        $('.e_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            console.log('Yoooo');
            console.log(suggestion);
            $(this).val('@' + suggestion.s__handle);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12274' + search_and_filter,
                    hitsPerPage: js_e___6404[31112]['m__message'],
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '@' + suggestion.s__handle;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="main__title"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }

        }]);

        $('.i_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.s__handle);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12273' + search_and_filter,
                    hitsPerPage: js_e___6404[31112]['m__message'],
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '#' + suggestion.s__handle;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="main__title"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);

    }
}


function view_s__title(suggestion){
    return htmlentitiesjs( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
}


function view_s_js_line(suggestion){
    return '<span class="icon-block">'+ view_cover_js(suggestion.s__cover) +'</span><span class="main__title">' + view_s__title(suggestion) + '</span><span class="grey">&nbsp;' + ( suggestion.s__type==12273 ? '/' : '@' ) + suggestion.s__handle + '</span>';
}

function view_s_js_cover(x__type, suggestion, action_id){

    if(!js_n___26010.includes(x__type)){
        alert('Missing type in JS UI');
        return false;
    }

    var background_image = '';
    var icon_image = '';

    if(suggestion.s__cover && suggestion.s__cover.length){
        if(validURL(suggestion.s__cover)){
            background_image = 'style="background-image:url(\''+suggestion.s__cover+'\')"';
        } else {
            icon_image = view_cover_js(suggestion.s__cover);
        }
    }

    //Return appropriate UI:
    if(x__type==26011){
        //Mini Coin
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="'+suggestion.s__url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26012){
        //Link Idea
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    }

}
function view_s_mini_js(s__cover,s__title){
    return '<span class="block-icon" title="'+s__title+'">'+ view_cover_js(s__cover) +'</span>';
}


function fetch_int_val(object_name){
    return ( $(object_name).length ? parseInt($(object_name).val()) : 0 );
}

function toggle_headline(x__type){

    var x__down = 0;
    var x__right = 0;
    var focus_card = fetch_int_val('#focus_card');
    if(focus_card==12273){
        x__right = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__down = fetch_int_val('#focus_id');
    }

    if($('.headline_title_' + x__type+' .icon_26008').hasClass('hidden')){

        //Currently open, must now be closed:
        var action_id = 26008; //Close
        $('.headline_title_' + x__type+ ' .icon_26008').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26007').addClass('hidden');
        $('.headline_body_' + x__type).addClass('hidden');

        if (x__type==6255){
            $('.navigate_12273').removeClass('active');
        }

    } else {

        //Close all other opens:
        $('.headlinebody').addClass('hidden');
        $('.headline_titles .icon_26007').addClass('hidden');
        $('.headline_titles .icon_26008').removeClass('hidden');

        //Currently closed, must now be opened...
        var action_id = 26007; //Open
        $('.headline_title_' + x__type+ ' .icon_26007').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26008').addClass('hidden');
        $('.headline_body_' + x__type).removeClass('hidden');

        if (x__type==6255){
            $('.navigate_12273').addClass('active');
        }

        //Scroll To:
        $('html, body').animate({
            scrollTop: $('.headline_body_' + x__type).offset().top
        }, 13);

    }

    //Log Transaction:
    x_create({
        x__creator: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}


function sort_e_load(x__type) {

    var sort_item_count = parseInt($('.headline_body_' + x__type).attr('read-counter'));
    console.log('Started Source Sorting for @'+x__type+' Counting: '+sort_item_count)

    if(!js_n___13911.includes(x__type)){
        //Does not support sorting:
        console.log('Not sortable')
        return false;
    } else if(sort_item_count<1 || sort_item_count>parseInt(js_e___6404[11064]['m__message'])){
        console.log('Not countable')
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-"+x__type);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log('No object')
            return false;
        }

        //Show sort icon:
        console.log('Completed Loading Sorting for @'+x__type)
        $('.sort_e_grab').removeClass('hidden');

        var sort = Sortable.create(theobject, {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: ".coinface-12274", // Specifies which items inside the element should be sortable
            handle: ".sort_e_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {
                sort_e_save(x__type);
            }
        });
    }, 377);

}


function toggle_pills(x__type){

    focus_card = x__type;
    var x__down = 0;
    var x__right = 0;
    var focus_card = fetch_int_val('#focus_card');

    if(focus_card==12273){
        x__right = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__down = fetch_int_val('#focus_id');
    }

    //Toggle view
    $('.xtypetitle').addClass('hidden');
    $('.xtypetitle_'+x__type).removeClass('hidden');


    if($('.thepill' + x__type+' .nav-link').hasClass('active')){

        var action_id = 26008; //Close

        //Hide all elements
        $('.nav-link').removeClass('active');
        $('.headlinebody').addClass('hidden');

    } else {

        //Currently closed, must now be opened:
        var action_id = 26007; //Open

        //Hide all elements
        $('.nav-link').removeClass('active');
        $('.headlinebody').addClass('hidden');
        $('.thepill' + x__type+ ' .nav-link').addClass('active');
        $('.headline_body_' + x__type).removeClass('hidden');

        //Do we need to load data via ajax?
        if( !$('.headline_body_' + x__type + ' .tab_content').html().length ){
            $('.headline_body_' + x__type + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="far fa-yin-yang fa-spin"></i></div>');
            load_tab(x__type, false);
        }
    }

    //Log Transaction:
    x_create({
        x__creator: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}



function i_copy(i__id, do_recursive){
    //Go ahead and delete:
    $.post("/i/i_copy", {
        i__id:i__id,
        do_recursive:do_recursive
    }, function (data) {
        if(data.status){
            js_redirect('/~'+data.new_i__hashtag);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}

function e_copy(e__id){
    //Go ahead and delete:
    $.post("/e/e_copy", {
        e__id:e__id
    }, function (data) {
        if(data.status){
            js_redirect('/@'+data.new_e__handle);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}



var busy_loading = [];
var current_page = [];
function view_load_page(x__type) {

    if(busy_loading[x__type] && parseInt(busy_loading[x__type])>0){
        return false;
    }
    busy_loading[x__type] = 1;

    if(!current_page[x__type]){
        current_page[x__type] = 1;
    }

    var current_total_count = parseInt($('.headline_body_' + x__type).attr('read-counter')); //Total of that item
    var has_more_to_load = ( current_total_count > parseInt(fetch_int_val('#page_limit')) * current_page[x__type] );
    var e_list = '#list-in-'+x__type;
    var current_top_x__id = $( e_list + ' .card_cover ' ).first().attr('x__id');
    var top_element = $('.cover_x_'+current_top_x__id);
    var e_loader = '<div class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading More...</div>';
    console.log(x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);

    if(!has_more_to_load){
        console.log('DONE LOADING: '+x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);
        return false;
    } else {
        console.log(x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);
    }


    current_page[x__type]++; //Now we can increment current page

    $(e_loader).insertAfter(e_list);
    $.post("/x/view_load_page", {
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        x__type: x__type,
        current_page: current_page[x__type],
    }, function (data) {
        $('.load-more').remove();
        if(data.length){

            $(e_list).append(data);
            x_set_start_text();
            load_card_clickers();
            $('[data-toggle="tooltip"]').tooltip();

            if(current_page<=1){
                window.scrollTo({
                    top: (top_element.offset().top - 59),
                    behavior: 'instant',
                });
            }

        }
        busy_loading[x__type] = 0;
    });


}




function js_view_shuffle_message(e__id){
    var messages = js_e___12687[e__id]['m__message'].split("\n");
    if(messages.length==1){
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random()*messages.length)];
    }
}


function loadtab(x__type, tab_data_id){

    //Hide all tabs:
    $('.tab-group-'+x__type).addClass('hidden');
    $('.tab-nav-'+x__type).removeClass('active');

    //Show this tab:
    $('.tab-group-'+x__type+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+x__type+'.tab-head-'+tab_data_id).addClass('active');

}


var init_in_process = 0;
function x_remove(x__id, x__type, i__hashtag){

    if(init_in_process==x__id){
        return false;
    }
    init_in_process = x__id;

    var r = confirm("Remove idea #"+i__hashtag+"?");
    if (!(r==true)) {
        return false;
    }

    //Save changes:
    $.post("/x/x_remove", {
        x__id:x__id
    }, function (data) {
        //Update UI to confirm with member:
        if (!data.status) {

            //There was some sort of an error returned!
            alert(data.message);

        } else {

            adjust_counter(x__type, -1);

            //REMOVE BOOKMARK from UI:
            $('.cover_x_'+x__id).fadeOut();

            setTimeout(function () {

                //Delete from body:
                $('.cover_x_'+x__id).remove();

            }, 233);
        }
    });

    return false;
}


function x_create(add_fields){
    return false;
    return $.post("/x/x_create", add_fields);
}


function update__cover(new_cover){
    $('#modal31912 .save_e__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
    has_unsaved_changes = true;
}
function image_cover(cover_preview, cover_apply, new_title){
    return '<a href="#preview_cover" onclick="update__cover(\''+cover_apply+'\')">' + view_s_mini_js(cover_preview, new_title) + '</a>';
}


function cover_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.coverUpload').hasClass('dynamic_saving')) {
        return false;
    }

    $('#upload_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="main__title">UPLOADING...</span>');

    if (isAdvancedUpload) {

        var ajaxData = new FormData($('.coverUpload').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.coverUpload').find('input[type="file"]').attr('name');
                if (typeof thename==typeof undefined || thename==false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('save_e__id', $('#modal31912 .save_e__id').val());

        $.ajax({
            url: '/x/cover_upload',
            type: $('.coverUpload').attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.coverUpload').removeClass('dynamic_saving');
            },
            success: function (data) {
                //Render new file:
                if(data.status){
                    $('#upload_results').html('');
                    update__cover(data.cdn_url);
                } else {
                    //Show error:
                    $('#upload_results').html(data.message);
                }
            },
            error: function (data) {
                //Show Error:
                $('#upload_results').html(data.responseText);
            }
        });
    } else {
        // ajax for legacy browsers
    }

}


function initiate_algolia(){
    $(".algolia_search").focus(function () {
        if(!algolia_index && parseInt(js_e___6404[12678]['m__message'])){
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });
}

function e_load_cover(x__type, e__id, counter, first_segment){

    if($('.coins_e_'+e__id+'_'+x__type).html().length){
        //Already loaded:
       return false;
    }

    $('.coins_e_'+e__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/e/e_load_cover", {
        x__type:x__type,
        e__id:e__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_e_'+e__id+'_'+x__type).html(data);
    });

}

function i_load_cover(x__type, i__id, counter, first_segment, current_e){

    if($('.coins_i_'+i__id+'_'+x__type).html().length){
        //Already loaded:
        return false;
    }

    $('.coins_i_'+i__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/i/i_load_cover", {
        x__type:x__type,
        i__id:i__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_i_'+i__id+'_'+x__type).html(data);
    });

}


//Main navigation
var search_on = false;
function toggle_search(){

    $('.left_nav').addClass('hidden');
    $('.icon_search').toggleClass('hidden');

    if(search_on){

        //Turn OFF
        search_on = false; //Reverse
        $('.max_width').removeClass('search_bar');
        $('.top_nav, #container_content').removeClass('hidden');
        $('.nav_search, #container_search').addClass('hidden');

    } else {

        //Turn ON
        search_on = true; //Reverse
        $('.max_width').addClass('search_bar');
        $('.top_nav, #container_content').addClass('hidden');
        $('.nav_search, #container_search').removeClass('hidden');
        $("#container_search .row").html(''); //Reset results view
        $('#top_search').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#top_search').focus();
        }, 55);


    }
}


function load_covers(){
    $(".load_e_covers, .load_i_covers").unbind();

    $(".load_e_covers").click(function(event) {
        e_load_cover($(this).attr('load_x__type'),$(this).attr('load_e__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
    $(".load_i_covers").click(function(event) {
        i_load_cover($(this).attr('load_x__type'),$(this).attr('load_i__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
}

function js_redirect(url, timer = 0){
    if(timer > 0){
        setTimeout(function () {
            window.location = url;
        }, timer);
    } else{
        window.location = url;
    }
    return false;
}

function load_card_clickers(){

    $(".card_click_e, .card_click_i").unbind();
    var ignore_clicks = 'a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click';

    $( ".card_click_e" ).click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            js_redirect('/@'+$(this).attr('e__handle'));
        }
    });

    $('.card_click_i').click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            js_redirect('/~'+$(this).attr('i__hashtag'));
        }
    });

}

var algolia_index = false;
$(document).ready(function () {

    $('.card_click_x').click(function(e) {
        js_redirect('/'+$(this).attr('i__hashtag'));
    });

    //Watchout for file uplods:
    $('.coverUpload').find('input[type="file"]').change(function () {
        cover_upload(droppedFiles, 'file');
    });

    load_covers();

    //Should we auto start?
    if (isAdvancedUpload) {
        var droppedFiles = false;
        $('.coverUpload').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $('.coverUploader').addClass('dynamic_saving');
            })
            .on('dragleave dragend drop', function () {
                $('.coverUploader').removeClass('dynamic_saving');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                cover_upload(droppedFiles, 'drop');
            });
    }

    //Lookout for textinput updates
    x_set_start_text();

    $('#top_search').keyup(function() {
        if(!$(this).val().length){
            $("#container_search .row").html(''); //Reset results view
        }
    });

    //For the S shortcut to load search:
    $("#top_search").focus(function() {
        if(!search_on){
            toggle_search();
        }
    });

    //Keep an eye for icon change:
    $('#modal31912 .save_e__cover').keyup(function() {
        update_cover_main($(this).val(), '.demo_cover');
    });

    set_autosize($('#sugg_note'));
    set_autosize($('.texttype__lg'));

    $('.trigger_modal').click(function (e) {
        var x__type = parseInt($(this).attr('x__type'));
        $('#modal'+x__type).modal('show');
        x_create({
            x__creator: js_pl_id,
            x__type: 14576, //MODAL VIEWED
            x__up: x__type,
        });
    });


    $("#modal31911, #modal31912").on("hide.bs.modal", function (e) {
        if(has_unsaved_changes){
            var r = confirm("Changes are unsaved! Close this window? Cancel to stay here:");
            if (!(r==true)) {
                e.preventDefault();
                return false;
            }
        }
    });


    //Load Algolia on Focus:
    initiate_algolia();


    //General ESC cancel
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) { //ESC

            if(search_on){
                toggle_search();
            }

        }
    });


    //Load tooltips:
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });


    //Prevent search submit:
    $('#searchFrontForm').on('submit', function(e) {
        e.preventDefault();
        return false;
    });


    if(parseInt(js_e___6404[12678]['m__message'])){

        var icons_listed = [];

        //TOP SEARCH
        $("#top_search").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    icons_listed = [];

                    //Members can filter search with first word:
                    var search_only_e = $("#top_search").val().charAt(0)=='@';
                    var search_only_in = $("#top_search").val().charAt(0)=='#';
                    var search_only_app = $("#top_search").val().charAt(0)=='-';
                    $("#container_search .row").html(''); //Reset results view


                    //Do not search if specific command ONLY:
                    if (( search_only_in || search_only_e || search_only_app ) && !isNaN($("#top_search").val().substr(1)) ) {

                        cb([]);
                        return;

                    } else {

                        //Now determine the filters we need to apply:
                        var search_filters = '';

                        if(search_only_in){
                            search_filters += ' s__type=12273';
                        } else if(search_only_e){
                            search_filters += ' s__type=12274';
                        } else if(search_only_app){
                            search_filters += ' s__type=6287';
                        }

                        if(js_pl_id > 0){

                            //For Members:
                            if(!js_session_superpowers_unlocked.includes(12701)){
                                //Can view limited sources:
                                if(search_filters.length>0){
                                    search_filters += ' AND ';
                                }
                                search_filters += ' ( _tags:publicly_searchable OR _tags:z_' + js_pl_id + ' ) ';
                            }

                        } else {

                            //Guest can search ideas only by default as they start typing;
                            if(search_filters.length>0){
                                search_filters += ' AND ';
                            }
                            search_filters += ' _tags:publicly_searchable ';

                        }

                        //Append filters:
                        algolia_index.search(q, {
                            hitsPerPage: js_e___6404[31113]['m__message'],
                            filters:search_filters,
                        }, function (error, content) {
                            if (error) {
                                cb([]);
                                return;
                            }
                            cb(content.hits, content);
                        });
                    }

                },
                templates: {
                    suggestion: function (suggestion) {
                        var item_key = suggestion.s__type+'_'+suggestion.s__id;
                        if(!icons_listed.includes(item_key)) {
                            icons_listed.push(item_key);
                            $("#container_search .row").append(view_s_js_cover(26011, suggestion, 0));
                        }
                        return false;
                    },
                    empty: function (data) {
                        $("#container_search .row").html('<div class="main__title margin-top-down-half"><span class="icon-block"><i class="fal fa-exclamation-circle"></i></span>No results found</div>');
                    },
                }
            }
        ]);
    }
});





function update_cover_main(cover_code, target_css){

    //Set Default:
    $(target_css+' .cover-link').css('background-image','');
    $(target_css+' .cover-btn').html('');

    //Update:
    if(validURL(cover_code)){
        $(target_css+' .cover-link').css('background-image','url(\''+cover_code+'\')');
    } else if(cover_code && cover_code.indexOf('fa-')>=0) {
        $(target_css+' .cover-btn').html('<i class="'+cover_code+'"></i>');
    } else if(cover_code && cover_code.length > 0) {
        $(target_css+' .cover-btn').text(cover_code);
    }
}

function view_cover_js(cover_code){
    if(cover_code && cover_code.length){
        if(validURL(cover_code)){
            return '<img src="'+cover_code+'" />';
        } else if(cover_code && cover_code.indexOf('fa-')>=0) {
            return '<i class="'+cover_code+'"></i>';
        } else {
            return cover_code;
        }
    } else {
        return '<i class="fas fa-circle zq12274"></i>';
    }
}

function update_cover_mini(cover_code, target_css){
    //Update:
    $(target_css).html(view_cover_js(cover_code));
}



function load_search(focus_card, x__type){
    if(js_n___11020.includes(x__type) || (focus_card==12274 && x__type==6255)){
        i_load_search(x__type);
    } else if(js_n___11028.includes(x__type) || (focus_card==12273 && x__type==6255)) {
        e_load_search(x__type);
    }
}


function i_load_search(x__type) {

    console.log(x__type + " i_load_search()");

    $('.new-list-'+x__type+' .add-input').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code==13) || (e.ctrlKey && code==13)) {
            e.preventDefault();
            return i__add(x__type, 0);
        }
    });

    if(!parseInt(js_e___6404[12678]['m__message'])){
        console.log("Search engine is disabled!");
        return false;
    }

    //Load Saerch:
    $('.new-list-'+x__type+' .add-input').keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{
        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {

                filters: 's__type=12273' + search_and_filter,
                hitsPerPage: js_e___6404[31112]['m__message'],

            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });

        },
        templates: {
            suggestion: function (suggestion) {
                $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_cover(26012, suggestion, x__type));
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="card_cover contrast_bg mini-cover coin-12273 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="black-background-obs cover-link isSelected"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            },
        }
    }]);
}

function e_load_search(x__type) {

    console.log(x__type + " e_load_search()");

    //Load Search:
    var icons_listed = [];
    $('.new-list-'+x__type + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code==13) || (e.ctrlKey && code==13)) {
            e__add(x__type, 0);
            return true;
        }
    });

    if(!parseInt(js_e___6404[12678]['m__message'])){
        console.log("Search engine is disabled!");
    }

    $('.new-list-'+x__type + ' .add-input').keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }
        icons_listed = [];

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{

        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {
                filters: 's__type=12274' + search_and_filter,
                hitsPerPage: js_e___6404[31112]['m__message'],
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        templates: {
            suggestion: function (suggestion) {
                var item_key = suggestion.s__type+'_'+suggestion.s__id;
                if(!icons_listed.includes(item_key)) {
                    icons_listed.push(item_key);
                    $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_cover(26013, suggestion, x__type));
                }
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="card_cover contrast_bg mini-cover coin-12274 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="black-background-obs cover-link coinType12274"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            }
        }
    }]);

}








function editor_load_i(i__id, x__id, link_i__id = 0){

    //Reset Fields:
    has_unsaved_changes = false;
    $("#modal31911 .unsaved_warning").val('');
    $('#modal31911 .save_results, #modal31911 .dynamic_editing_radio').html('');
    $("#modal31911 .dynamic_item, #modal31911 .save_x__message").addClass('hidden');
    $("#modal31911 .dynamic_editing_loading").removeClass('hidden');
    $('#modal31911 .save_i__id, #modal31911 .save_x__id').val(0);
    $("#modal31911 .dynamic_item").attr('placeholder', '').val('').attr('d__id','');

    //Load Instant Fields:
    if(link_i__id){
        $("#modal31911 .show_id").text('Link to '+link_i__id);
        $('#modal31911 .link_i__id').val(i__id);
    }
    if(i__id){
        $('#modal31911 .save_i__id').val(i__id);
        $("#modal31911 .show_id").text('ID '+i__id);
        $('#modal31911 .save_i__hashtag').val($('.ui_i__hashtag_'+i__id).text());
        $('#modal31911 .save_i__message').val($('.ui_i__message_'+i__id).text()).focus();
    }
    if(x__id){
        $('#modal31911 .save_x__id').val(x__id);
        //$('#modal31911 .save_x__message').val($('.ui_x__message_'+x__id).text()).removeClass('hidden');
    }

    //Activate Modal:
    $('#modal31911').modal('show');

    activate_handle_search($('#modal31911 .save_i__message'));

    setTimeout(function () {
        set_autosize($('#modal31911 .save_i__message'));
        set_autosize($('#modal31911 .save_x__message'));
    }, 377);

    if(i__id){
        //Load dynamic data:
        $.post("/i/editor_load_i", {
            i__id: i__id,
            x__id: x__id,
        }, function (data) {

            $("#modal31911 .dynamic_editing_loading").addClass('hidden');

            if (data.status) {

                var field_counter = 0;

                //Dynamic Input Fields:
                for (var i=0, item; item = data.return_inputs[i]; i++) {
                    field_counter++;
                    $("#modal31911 .dynamic_"+field_counter+" h3").html(item["d__title"]);
                    $("#modal31911 .dynamic_"+field_counter).removeClass('hidden');
                    $("#modal31911 .dynamic_"+field_counter+" input").attr('placeholder',item["d__placeholder"]).val(item["d__value"]);
                }

                //Dynamic Radio fields (if any):
                $("#modal31911 .dynamic_editing_radio").html(data.return_radios);

                $('[data-toggle="tooltip"]').tooltip();

            } else {

                //Should not have an issue loading...
                alert('ERROR:' + data.message);

            }
        });
    } else {
        $("#modal31911 .dynamic_editing_loading").addClass('hidden');
    }

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31911 .unsaved_warning").change(function() {
        has_unsaved_changes = true;
    });

}


var i_saving = false; //Prevent double saving
function editor_save_i(){

    if(i_saving){
        return false;
    } else {
        i_saving = true;
    }

    var modify_data = {
        save_i__id:         $('#modal31911 .save_i__id').val(),
        save_x__id:         $('#modal31911 .save_x__id').val(),
        save_x__message:    $('#modal31911 .save_x__message').val().trim(),
        save_i__message:    $('#modal31911 .save_i__message').val().trim(),
        save_i__hashtag:    $('#modal31911 .save_i__hashtag').val().trim(),
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31911 .save_dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31911 .save_dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31911 .save_dynamic_'+i).val().trim();
        }
    }

    $.post("/i/editor_save_i", modify_data, function (data) {

        if (!data.status) {

            //Show Errors:
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error:<br />'+data.message);

        } else {

            //Reset errors:
            $("#modal31911 .save_results").html('');
            has_unsaved_changes = false;
            $('#modal31911').modal('hide');

            //Update Idea Message:
            $('.ui_i__message_'+modify_data['save_i__id']).text(modify_data['save_i__message']);

            //Update Hashtag:
            $(".ui_i__hashtag_"+modify_data['save_i__id']).text(modify_data['save_i__hashtag']).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash

            $('.ui_i__cache_'+modify_data['save_i__id']).html(( parseInt($('.ui_i__cache_'+modify_data['save_i__id']).attr('show_cache_links')) ? data.return_i__cache_links : data.return_i__cache ));
            console.log(data.message); //To check what happened...

            if(x__id){
                $('.ui_x__message_'+x__id).text(modify_data['save_x__message']);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Load Images:
            i_saving = false;

        }
    });
}





function editor_load_e(e__id, x__id){

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results, #modal31912 .dynamic_editing_radio').html('');
    $("#modal31912 .dynamic_item, #modal31912 .save_x__message").addClass('hidden');
    $("#modal31912 .dynamic_editing_loading").removeClass('hidden');
    $("#modal31912 .dynamic_item").attr('placeholder', '').val('').attr('d__id','');

    //Source resets:
    $('#search_cover').val('');
    $("#upload_results, #previous_used_covers").html('');
    $('#modal31912 .black-background-obs').removeClass('isSelected');

    //Load Instant Fields:
    $('#modal31912 .save_e__id').val(e__id);
    $('#modal31912 .save_x__id').val(x__id);
    $("#modal31912 .show_id").text('ID '+e__id);
    $('#modal31912 .save_e__handle').val($('.ui_e__handle_'+e__id).text());

    $('#modal31912 .save_e__title').val($('.text__6197_'+e__id).val());
    var current_cover = $('.ui_e__cover_'+e__id).attr('raw_cover');
    $('#modal31912 .save_e__cover').val(current_cover).focus();
    update_cover_main(current_cover, '.demo_cover');

    if(x__id){
        $('#modal31912 .save_x__message').val($('.ui_x__message_'+x__id).text()).removeClass('hidden');
        setTimeout(function () {
            set_autosize($('#modal31912 .save_x__message'));
        }, 377);
    }

    //Activate Modal:
    $('#modal31912').modal('show');


    $.post("/e/editor_load_e", {
        e__id: e__id,
        x__id: x__id
    }, function (data) {

        $("#modal31912 .dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            var field_counter = 0;

            //Dynamic Input Fields:
            for (var i=0, item; item = data.return_inputs[i]; i++) {
                field_counter++;
                $("#modal31912 .dynamic_"+field_counter+" h3").html(item["d__title"]);
                $("#modal31912 .dynamic_"+field_counter).removeClass('hidden');
                $("#modal31912 .dynamic_"+field_counter+" input").attr('placeholder',item["d__placeholder"]).val(item["d__value"]).attr('d__id',item["d__id"]);
            }

            //Dynamic Radio fields (if any):
            $("#modal31912 .dynamic_editing_radio").html(data.return_radios);

            $('[data-toggle="tooltip"]').tooltip();

            //Any Source suggestions to auto load?
            if(data.previous_used_covers.length){
                data.previous_used_covers.forEach(function(item) {
                    $("#previous_used_covers").append(image_cover(item.cover_preview, item.cover_apply, item.new_title));
                });
            }

        } else {

            //Should not have an issue loading...
            alert('ERROR:' + data.message);

        }

    });

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31912 .unsaved_warning").change(function() {
        has_unsaved_changes = true;
    });

}

e_saving = false;
function editor_save_e(){

    if(e_saving){
        return false;
    } else {
        e_saving = true;
    }

    var modify_data = {
        save_e__id:         $('#modal31912 .save_e__id').val(),
        save_e__title:      $('#modal31912 .save_e__title').val().trim(),
        save_e__cover:      $('#modal31912 .save_e__cover').val().trim(),
        save_e__handle:     $('#modal31912 .save_e__handle').val().trim(),
        save_x__id:         $('#modal31912 .save_x__id').val(),
        save_x__message:    $('#modal31912 .save_x__message').val().trim(),
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31912 .save_dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31912 .save_dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31912 .save_dynamic_'+i).val().trim();
        }
    }

    $.post("/e/editor_save_e", modify_data, function (data) {

        if (!data.status) {

            //Show Errors:
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error:<br />'+data.message);

        } else {

            //Reset errors:
            $("#modal31912 .save_results").html('');

            //Update Title:
            update_text_name(6197, modify_data['save_e__id'], modify_data['save_e__title']);

            //Update Handle:
            $('.ui_e__handle_'+modify_data['save_e__id']).text(modify_data['save_e__handle']);

            //Update Mini Icon:
            update_cover_mini(modify_data['save_e__cover'], '.mini_6197_'+modify_data['save_e__id']);

            //Update Main Icons:
            update_cover_main(modify_data['save_e__cover'], '.s__12274_'+modify_data['save_e__id']);

            console.log(data.message); //To check what happened...

            if( modify_data['save_x__id'] ){
                $('.ui_x__message_'+ modify_data['save_x__id'] ).text(modify_data['save_x__message']);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            e_saving = false;
            has_unsaved_changes = false;
            $('#modal31912').modal('hide');

        }

    });

}



















function load_tab(x__type, auto_load){

    var focus_card = fetch_int_val('#focus_card');
    console.log('Tab loading... from @'+focus_card+' for @'+x__type);

    if(focus_card==12273){

        $.post("/i/view_body_i", {
            focus_card:focus_card,
            x__type:x__type,
            counter:$('.headline_body_' + x__type).attr('read-counter'),
            i__id:fetch_int_val('#focus_id')
        }, function (data) {
            $('.headline_body_' + x__type + ' .tab_content').html(data);
        });

    } else if(focus_card==12274){

        //Load the tab:
        $.post("/e/view_body_e", {
            focus_card:focus_card,
            x__type:x__type,
            counter:$('.headline_body_'+x__type).attr('read-counter'),
            e__id:fetch_int_val('#focus_id')
        }, function (data) {
            $('.headline_body_'+x__type + ' .tab_content').html(data);
        });

    } else {

        //Whaaaat is this?
        console.log('ERROR: Unknown Tab!');
        return false;

    }

    //Give some extra loding time so the content loads on page:
    setTimeout(function () {

        $('[data-toggle="tooltip"]').tooltip();
        load_card_clickers();
        initiate_algolia();
        load_editor();
        x_set_start_text();
        set_autosize($('.x_set_class_text'));

        setTimeout(function () {
            load_covers();
            $('[data-toggle="tooltip"]').tooltip();
        }, 2584);


        $(function () {
            var $win = $(window);
            $win.scroll(function () {
                //Download loading from bottom:
                if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) <= 377) {
                    view_load_page(x__type);
                }
            });
        });

        if(js_n___11020.includes(x__type) || (focus_card==12274 && x__type==6255)){
            setTimeout(function () {
                sort_i_load(x__type);
            }, 2584);
        } else if(js_n___11028.includes(x__type) || (focus_card==12273 && x__type==6255)) {
            setTimeout(function () {
                sort_e_load(x__type);
            }, 2584);
        }

        load_covers();

    }, 2584);



}


var i_is_adding = false;
function i__add(x__type, link_i__id) {

    /*
     *
     * Either creates an IDEA transaction between focus_id & link_i__id
     * OR will create a new idea based on input text and then transaction it
     * to fetch_int_val('#focus_id') (In this case link_i__id=0)
     *
     * */

    if(i_is_adding){
        return false;
    }

    //Remove results:
    $('.mini-cover.coin-12273.coin-id-'+link_i__id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');
    i_is_adding = true;
    var sort_i_grab = ".card_cover";
    var input_field = $('.new-list-'+x__type+' .add-input');
    var new_i__message = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && new_i__message.length < 1) {
        alert('Missing Idea Title');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list(x__type, sort_i_grab, '<div id="tempLoader" class="col-6 col-md-4 no-padding show_all_i"><div class="cover-wrapper"><div class="black-background-obs cover-link"><div class="cover-btn"><i class="far fa-yin-yang fa-spin"></i></div></div></div></div>', 0);

    //Update backend:
    $.post("/i/i__add", {
        x__type: x__type,
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        new_i__message: new_i__message,
        link_i__id: link_i__id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            sort_i_load(x__type);

            //Add new
            add_to_list(x__type, sort_i_grab, data.new_i_html, 1);

            //Lookout for textinput updates
            x_set_start_text();
            load_covers();
            set_autosize($('.texttype__lg'));

            //Hide Coin:
            $('.mini-cover.coin-12273.coin-id-'+link_i__id).fadeOut();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}

function toggle_max_view(css_class){

    //Toggle main class:
    $('.'+css_class).toggleClass('hidden');

    if($( ".fixed-top" ).hasClass( "maxcontain" )){
        //Minimize:
        $('.maxcontain').addClass('container').removeClass('maxcontain');
    } else {
        //Maximize:
        $('.container').addClass('maxcontain').removeClass('container');
    }

}


//Adds OR transactions sources to sources
var e_is_adding = false;
function e__add(x__type, e_existing_id) {

    if(e_is_adding){
        return false;
    }

    //if e_existing_id>0 it means we're adding an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then adding it, in which case e_new_string is required
    e_is_adding = true;

    var input = $('.new-list-'+x__type+' .add-input');

    var original_photo = $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html();
    $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');
    var e_new_string = null;
    if (e_existing_id==0) {
        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    $.post("/e/e__add", {

        focus_card: fetch_int_val('#focus_card'),
        x__type: x__type,
        focus_id: fetch_int_val('#focus_id'),
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        e_is_adding = false;

        if (data.status) {

            if(data.e_already_linked){
                var r = confirm("This is already linked here! Are you sure you want to double link it?");
                if (r==true) {
                    data.e_already_linked = false;
                } else {
                    $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html(original_photo);
                }
            }

            if(!data.e_already_linked){

                //Raw input to make it ready for next URL:
                //input.focus();

                //Add new object to list:
                add_to_list(x__type, '.coinface-12274', data.e_new_echo, 1);

                //Allow inline editing if enabled:
                x_set_start_text();

                sort_e_load(x__type);
                load_covers();

                //Hide Coin:
                $('.mini-cover.coin-12274.coin-id-'+e_existing_id).fadeOut();
            }

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}



function e_delete(x__id, x__type) {

    var r = confirm("Unlink this source?");
    if (r==true) {
        $.post("/e/e_delete", {

            x__id: x__id,

        }, function (data) {
            if (data.status) {

                adjust_counter(x__type, -1);
                $(".cover_x_" + x__id).fadeOut();
                setTimeout(function () {
                    $(".cover_x_" + x__id).remove();
                }, 610);

            } else {
                //We had an error:
                alert(data.message);
            }
        });
    }
}




//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

function x_link_toggle(x__type, i__id){

    $('.btn_toggle_'+x__type).toggleClass('hidden');
    var x__id = parseInt($('.btn_control_'+x__type).attr('current_x_id'));

    if(!x__id){
        //Add:
        $.post("/x/x_link_toggle", {
            x__type:x__type,
            i__id:i__id,
            top_i__id:$('#top_i__id').val(),
        }, function (data) {
            if (!data.status) {
                alert(data.message);
                $('.btn_toggle_'+x__type).toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.btn_control_'+x__type).attr('current_x_id', data.x__id);
            }
        });
    } else {
        //REMOVE
        $.post("/x/x_remove", {
            x__id:x__id
        }, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
                $('.btn_toggle_'+x__type).toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.btn_control_'+x__type).attr('current_x_id', 0);
            }
        });
    }
}



function validURL(str) {
    return str && str.length && str.substring(0, 4)=='http';
}


function add_to_list(x__type, sort_i_grab, html_content, increment) {

    adjust_counter(x__type, increment);

    //See if we previously have a list in place?
    if ($("#list-in-" + x__type + " " + sort_i_grab).length > 0) {
        //Downwards add to start"
        $("#list-in-" + x__type + " " + sort_i_grab + ":first").before(html_content);
    } else {
        //Raw list, add before input filed:
        $("#list-in-" + x__type).prepend(html_content);
    }


    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

}

jQuery.fn.extend({
    insertAtCaret: function (myValue) {
        return this.each(function (i) {
            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            } else if (this.selectionStart || this.selectionStart=='0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos) + myValue + this.value.substring(endPos, this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        })
    }
});





Math.fmod = function (a,b) { return Number((a - (Math.floor(a / b) * b)).toPrecision(8)); };

function images_add(image_url, image_title){
    var current_value = $('.new_i__message').val();
    $('#modal14073').modal('hide');
    $('.new_i__message').val(( current_value.length ? current_value+"\n\n" : '' ) + image_url + '?e__title='+encodeURI(image_title));
}


function x_set_start_text(){
    $('.x_set_class_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code==13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });
}

function update_text_name(cache_e__id, e__id, e__title){
    var target_element = ".text__"+cache_e__id+"_" + e__id;
    $(target_element).text(e__title).attr('old-value', e__title); //.val(e__title)
    set_autosize($(target_element));
}

function x_set_text(this_grabr){

    var modify_data = {
        s__id: parseInt($(this_grabr).attr('s__id')),
        cache_e__id: parseInt($(this_grabr).attr('cache_e__id')),
        new_i__message: $(this_grabr).val().trim()
    };

    //See if anything changes:
    if( $(this_grabr).attr('old-value')==modify_data['new_i__message'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var target_element = '.text__'+modify_data['cache_e__id']+'_'+modify_data['s__id'];
    $(target_element).addClass('dynamic_saving').prop("disabled", true);

    $.post("/x/x_set_text", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(target_element).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_e__id'], modify_data['s__id'], modify_data['new_i__message']);

        }

        setTimeout(function () {
            //Restore background:
            $(target_element).removeClass('dynamic_saving').prop("disabled", false);
        }, 233);

    });
}




function adjust_counter(x__type, adjustment_count){
    var current_total_count = parseInt($('.headline_body_' + x__type).attr('read-counter')) + adjustment_count;
    $('.xtypecounter'+x__type).text(current_total_count);

}




function activate_handle_search(obj) {
    if(parseInt(js_e___6404[12678]['m__message'])){
        obj.textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    algolia_index.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12274' + search_and_filter,
                    })
                        .then(function searchSuccess(content) {
                            if (content.query === q) {
                                callback(content.hits);
                            }
                        })
                        .catch(function searchFailure(err) {
                            console.error(err);
                        });
                },
                template: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                replace: function (suggestion) {
                    set_autosize(obj);
                    return ' @' + suggestion.s__handle + ' ';
                }
            },
            {
                match: /(^|\s)#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    algolia_index.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12273' + search_and_filter,
                    })
                        .then(function searchSuccess(content) {
                            if (content.query === q) {
                                callback(content.hits);
                            }
                        })
                        .catch(function searchFailure(err) {
                            console.error(err);
                        });
                },
                template: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                replace: function (suggestion) {
                    set_autosize(obj);
                    return ' #' + suggestion.s__handle + ' ';
                }
            },
        ]);
    }
}


function set_autosize(theobject){
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}



var sorting_loaded = []; // more efficient than new Array()

function sort_i_load(x__type){

    if(!js_n___4603.includes(x__type)){
        console.log(x__type+' is not sortable');
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-" + x__type);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log(x__type+' failed to find sortable object');
            return false;
        }

        if(sorting_loaded.indexOf(x__type) >= 0){
            console.log(x__type+' already loaded');
            return false;
        }

        //Make sure beow minimum sorting requirement:
        if($("#list-in-"+x__type+" .sort_draggable").length>=parseInt(fetch_int_val('#page_limit'))){
            return false;
        }

        $('.sort_i_grab').removeClass('hidden');
        console.log(x__type+' sorting load success');
        sorting_loaded.push(x__type);

        //Load sorter:
        var sort = Sortable.create(theobject, {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: "#list-in-"+x__type+" .sort_draggable", // Specifies which items inside the element should be sortable
            handle: "#list-in-"+x__type+" .sort_i_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {

                var sort_rank = 0;
                var new_x_order = [];
                $("#list-in-"+x__type+" .sort_draggable").each(function () {
                    var x__id = parseInt($(this).attr('x__id'));
                    if(x__id > 0){
                        sort_rank++;
                        new_x_order[sort_rank] = x__id;
                    }
                });

                //Update order:
                if(sort_rank > 0){
                    $.post("/x/sort_i_load", { new_x_order:new_x_order, x__type:x__type }, function (data) {
                        //Update UI to confirm with member:
                        if (!data.status) {
                            //There was some sort of an error returned!
                            alert(data.message);
                        }
                    });
                }
            }
        });
    }, 377);

}









var current_focus = 0;
function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    $('body').removeClass(the_class);
}

function e_radio(focus_id, selected_e__id, enable_mulitiselect, down_e__id, right_i__id){

    //Any warning needed?
    if(js_n___31780.includes(selected_e__id) && !confirm(js_e___31780[selected_e__id]['m__message'])){
        return false;
    }

    var was_previously_selected = ( $('.radio-'+focus_id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Customizable Theme?
    if(js_n___13890.includes(focus_id)){
        current_focus = focus_id;
        $('body').removeClass('custom_ui_'+focus_id+'_');
        window['js_n___'+focus_id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+focus_id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+focus_id+' .item-'+selected_e__id+' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+focus_id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+focus_id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+focus_id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_radio", {
        focus_id: focus_id,
        down_e__id: down_e__id,
        right_i__id: right_i__id,
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        $(notify_el).html(initial_icon);

        if (!data.status) {
            alert(data.message);
        }

    });


}


function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}


function update_dropdown(element_id, new_e__id, o__id, x__id, show_full_name){

    /*
    *
    * WARNING:
    *
    * element_id Must be listed as followers of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropi_'+element_id+'_'+o__id+'_'+x__id+'.active').attr('current-selected'));
    new_e__id = parseInt(new_e__id);
    if(current_selected==new_e__id){
        //Nothing changed:
        return false;
    }



    //Deleting Anything?
    var migrate_s__id = 0;
    if(element_id==31004 && !(new_e__id in js_e___31871)){

        //Deleting Idea:
        var confirm_removal = prompt("Are you sure you want to delete this idea?\nEnter 0 to unlink OR enter Idea ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_s__id = confirm_removal;

    } else if(element_id==6177 && !(new_e__id in js_e___7358)){

        //Deleting Source:
        var confirm_removal = prompt("Are you sure you want to delete this source?\nEnter 0 to unlink OR enter source ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_s__id = confirm_removal;

    }



    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    if(!data_object[new_e__id]){
        alert('Invalid element ID: '+element_id +'/'+ new_e__id +'/'+ o__id +'/'+ x__id +'/'+ show_full_name);
        return false;
    }
    $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="main__title">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/x/update_dropdown", {
        focus_id:fetch_int_val('#focus_id'),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
        migrate_s__id: migrate_s__id,
        x__id: x__id
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m__cover']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .dropi_' + element_id +'_'+o__id+ '_' + x__id).removeClass('active');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .optiond_' + new_e__id+'_'+o__id+ '_' + x__id).addClass('active');

            var selected_e__id = $('.dropd_'+element_id+'_'+o__id+'_'+x__id).attr('selected-val');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id).attr('selected-val' , new_e__id);

            if(element_id==6177){
                //Update source access:
                $('.s__12274_'+o__id+' .cover-link').removeClass('card_access_'+selected_e__id).addClass('card_access_'+new_e__id);
            }

            if( data.deletion_redirect && data.deletion_redirect.length > 0 ){

                //Go to main idea page:
                js_redirect(data.deletion_redirect);

            } else if( data.delete_element && data.delete_element.length > 0 ){

                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $( data.delete_element ).fadeOut();

                    setTimeout(function () {
                        //Restore background:
                        $( data.delete_element ).remove();
                    }, 55);

                }, 377);

            }

            if( data.trigger_i_save_modal ){
                //We need to show idea modal:
                editor_load_i(o__id, $('.s__12273_'+o__id).attr('x__id'));
            }

        } else {

            //Reset to default:
            var current_class = $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn span').attr('class');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="'+current_class+'">'+data_object[current_selected]['m__cover']+'</span>' + ( show_full_name ? data_object[current_selected]['m__title'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}








function e_reset_discoveries(e__id){
    //Confirm First:
    var r = confirm("DANGER WARNING!!! You are about to delete your ENTIRE discovery history. This action cannot be undone and you will lose all your discovery coins.");
    if (r==true) {
        $('.e_reset_discoveries').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="main__title">REMOVING ALL...</b>');

        //Redirect:
        js_redirect('/x/e_reset_discoveries/'+e__id);
    } else {
        return false;
    }
}


function sort_e_save(x__type) {

    var new_x__weight = [];
    var sort_rank = 0;

    $("#list-in-"+x__type+" .coinface-12274").each(function () {
        //Fetch variables for this idea:
        var e__id = parseInt($(this).attr('e__id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__weight[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/sort_e_save", {e__id: fetch_int_val('#focus_id'), x__type:x__type, new_x__weight: new_x__weight}, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function sort_alphabetical(){
    var r = confirm("Reset sorting alphabetically?");
    if (r==true) {

        var focus_card = fetch_int_val('#focus_card');
        var focus_id = fetch_int_val('#focus_id');
        var focus_handle = fetch_int_val('#focus_handle');


        //Update via call:
        $.post("/x/sort_alphabetical", {
            focus_card: focus_card,
            focus_id: focus_id
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                if(focus_card==12273){
                    js_redirect('/~' + focus_handle);
                } else if(focus_card==12274){
                    js_redirect('/@' + focus_handle);
                }

            }
        });
    }
}






