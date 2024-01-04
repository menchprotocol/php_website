
//Emoji Picker:
const EmojiPicker = function(options) {

    this.options = options;
    this.trigger = this.options.trigger.map(item => item.selector);
    this.insertInto = undefined;
    let emojiesHTML = '';
    let categoriesHTML = '';
    let emojiList = undefined;
    let moseMove = false;
    const pickerWidth = this.options.closeButton ? 370 : 350;
    const pickerHeight = 400;

    this.lib = function(el = undefined) {

        const isNodeList = (nodes) => {
            var stringRepr = Object.prototype.toString.call(nodes);

            return typeof nodes === 'object' &&
                /^\[object (HTMLCollection|NodeList|Object)\]$/.test(stringRepr) &&
                (typeof nodes.length === 'number') &&
                (nodes.length === 0 || (typeof nodes[0] === "object" && nodes[0].nodeType > 0));
        }

        return {

            el: () => {
                // Check if is node
                if (!el) {
                    return undefined;
                } else if (el.nodeName) {
                    return [el];
                } else if (isNodeList(el)) {
                    return Array.from(el)
                } else if (typeof(el) === 'string' || typeof(el) === 'STRING') {
                    return Array.from(document.querySelectorAll(el));
                } else {
                    return undefined;
                }
            },

            on(event, callback, classList = undefined) {
                if (!classList) {
                    this.el().forEach(item => {
                        item.addEventListener(event, callback.bind(item))
                    })
                } else {
                    this.el().forEach(item => {
                        item.addEventListener(event, (e) => {
                            if (e.target.closest(classList)) {

                                let attr = undefined;

                                if (Array.isArray(classList)) {
                                    const stringifiedElem = e.target.outerHTML;

                                    const index = classList.findIndex(attr => stringifiedElem.includes(attr.slice(1)));

                                    attr = classList[index];
                                }

                                callback(e, attr)
                            }
                        })
                    })
                }
            },

            css(params) {
                for (const key in params) {
                    if (Object.hasOwnProperty.call(params, key)) {
                        const cssVal = params[key];
                        this.el().forEach(el => el.style[key] = cssVal)
                    }
                }
            },

            attr(param1, param2 = undefined) {

                if (!param2) {
                    return this.el()[0].getAttribute(param1)
                }
                this.el().forEach(el => el.setAttribute(param1, param2))
            },

            removeAttr(param) {
                this.el().forEach(el => el.removeAttribute(param))
            },

            addClass(param) {
                this.el().forEach(el => el.classList.add(param))
            },

            removeClass(param) {
                this.el().forEach(el => el.classList.remove(param))
            },

            slug(str) {
                return str
                    .toLowerCase()
                    .replace(/[^\u00BF-\u1FFF\u2C00-\uD7FF\w]+|[\_]+/ig, '-')
                    .replace(/ +/g,'-')
                    ;
            },

            remove(param) {
                this.el().forEach(el => el.remove())
            },

            val(param = undefined) {
                let val;

                if (param === undefined) {
                    this.el().forEach(el => {
                        val = el.value;
                    })
                } else {
                    this.el().forEach(el => {
                        el.value = param;
                    })
                }

                return val;
            },

            text(msg = undefined) {
                if (msg === undefined) {
                    return el.innerText;
                } else {
                    this.el().forEach(el => {
                        el.innerText = msg;
                    })
                }
            },

            html(data = undefined) {
                if (data === undefined) {
                    return el.innerHTML;
                } else {
                    this.el().forEach(el => {
                        el.innerHTML = data;
                    })
                }
            }
        }
    };

    const emojiObj = {
        'People': [
            {
                "emoji": "😀",
                "title": "Grinning Face"
            },
            {
                "emoji": "😃",
                "title": "Grinning Face with Big Eyes"
            },
            {
                "emoji": "😄",
                "title": "Grinning Face with Smiling Eyes"
            },
            {
                "emoji": "😁",
                "title": "Beaming Face with Smiling Eyes"
            },
            {
                "emoji": "😆",
                "title": "Grinning Squinting Face"
            },
            {
                "emoji": "😅",
                "title": "Grinning Face with Sweat"
            },
            {
                "emoji": "🤣",
                "title": "Rolling on the Floor Laughing"
            },
            {
                "emoji": "😂",
                "title": "Face with Tears of Joy"
            },
            {
                "emoji": "🙂",
                "title": "Slightly Smiling Face"
            },
            {
                "emoji": "🙃",
                "title": "Upside-Down Face"
            },
            {
                "emoji": "😉",
                "title": "Winking Face"
            },
            {
                "emoji": "😊",
                "title": "Smiling Face with Smiling Eyes"
            },
            {
                "emoji": "😇",
                "title": "Smiling Face with Halo"
            },
            {
                "emoji": "🥰",
                "title": "Smiling Face with Hearts"
            },
            {
                "emoji": "😍",
                "title": "Smiling Face with Heart-Eyes"
            },
            {
                "emoji": "🤩",
                "title": "Star-Struck"
            },
            {
                "emoji": "😘",
                "title": "Face Blowing a Kiss"
            },
            {
                "emoji": "😗",
                "title": "Kissing Face"
            },
            {
                "emoji": "☺️",
                "title": "Smiling Face"
            },
            {
                "emoji": "😚",
                "title": "Kissing Face with Closed Eyes"
            },
            {
                "emoji": "😙",
                "title": "Kissing Face with Smiling Eyes"
            },
            {
                "emoji": "🥲",
                "title": "Smiling Face with Tear"
            },
            {
                "emoji": "😋",
                "title": "Face Savoring Food"
            },
            {
                "emoji": "😛",
                "title": "Face with Tongue"
            },
            {
                "emoji": "😜",
                "title": "Winking Face with Tongue"
            },
            {
                "emoji": "🤪",
                "title": "Zany Face"
            },
            {
                "emoji": "😝",
                "title": "Squinting Face with Tongue"
            },
            {
                "emoji": "🤑",
                "title": "Money-Mouth Face"
            },
            {
                "emoji": "🤗",
                "title": "Smiling Face with Open Hands"
            },
            {
                "emoji": "🤭",
                "title": "Face with Hand Over Mouth"
            },
            {
                "emoji": "🤫",
                "title": "Shushing Face"
            },
            {
                "emoji": "🤔",
                "title": "Thinking Face"
            },
            {
                "emoji": "🤐",
                "title": "Zipper-Mouth Face"
            },
            {
                "emoji": "🤨",
                "title": "Face with Raised Eyebrow"
            },
            {
                "emoji": "😐",
                "title": "Neutral Face"
            },
            {
                "emoji": "😑",
                "title": "Expressionless Face"
            },
            {
                "emoji": "😶",
                "title": "Face Without Mouth"
            },
            {
                "emoji": "😶‍🌫️",
                "title": "Face in Clouds"
            },
            {
                "emoji": "😏",
                "title": "Smirking Face"
            },
            {
                "emoji": "😒",
                "title": "Unamused Face"
            },
            {
                "emoji": "🙄",
                "title": "Face with Rolling Eyes"
            },
            {
                "emoji": "😬",
                "title": "Grimacing Face"
            },
            {
                "emoji": "😮‍💨",
                "title": "Face Exhaling"
            },
            {
                "emoji": "🤥",
                "title": "Lying Face"
            },
            {
                "emoji": "😌",
                "title": "Relieved Face"
            },
            {
                "emoji": "😔",
                "title": "Pensive Face"
            },
            {
                "emoji": "😪",
                "title": "Sleepy Face"
            },
            {
                "emoji": "🤤",
                "title": "Drooling Face"
            },
            {
                "emoji": "😴",
                "title": "Sleeping Face"
            },
            {
                "emoji": "😷",
                "title": "Face with Medical Mask"
            },
            {
                "emoji": "🤒",
                "title": "Face with Thermometer"
            },
            {
                "emoji": "🤕",
                "title": "Face with Head-Bandage"
            },
            {
                "emoji": "🤢",
                "title": "Nauseated Face"
            },
            {
                "emoji": "🤮",
                "title": "Face Vomiting"
            },
            {
                "emoji": "🤧",
                "title": "Sneezing Face"
            },
            {
                "emoji": "🥵",
                "title": "Hot Face"
            },
            {
                "emoji": "🥶",
                "title": "Cold Face"
            },
            {
                "emoji": "🥴",
                "title": "Woozy Face"
            },
            {
                "emoji": "😵",
                "title": "Face with Crossed-Out Eyes"
            },
            {
                "emoji": "😵‍💫",
                "title": "Face with Spiral Eyes"
            },
            {
                "emoji": "🤯",
                "title": "Exploding Head"
            },
            {
                "emoji": "🤠",
                "title": "Cowboy Hat Face"
            },
            {
                "emoji": "🥳",
                "title": "Partying Face"
            },
            {
                "emoji": "🥸",
                "title": "Disguised Face"
            },
            {
                "emoji": "😎",
                "title": "Smiling Face with Sunglasses"
            },
            {
                "emoji": "🤓",
                "title": "Nerd Face"
            },
            {
                "emoji": "🧐",
                "title": "Face with Monocle"
            },
            {
                "emoji": "😕",
                "title": "Confused Face"
            },
            {
                "emoji": "😟",
                "title": "Worried Face"
            },
            {
                "emoji": "🙁",
                "title": "Slightly Frowning Face"
            },
            {
                "emoji": "☹️",
                "title": "Frowning Face"
            },
            {
                "emoji": "😮",
                "title": "Face with Open Mouth"
            },
            {
                "emoji": "😯",
                "title": "Hushed Face"
            },
            {
                "emoji": "😲",
                "title": "Astonished Face"
            },
            {
                "emoji": "😳",
                "title": "Flushed Face"
            },
            {
                "emoji": "🥺",
                "title": "Pleading Face"
            },
            {
                "emoji": "😦",
                "title": "Frowning Face with Open Mouth"
            },
            {
                "emoji": "😧",
                "title": "Anguished Face"
            },
            {
                "emoji": "😨",
                "title": "Fearful Face"
            },
            {
                "emoji": "😰",
                "title": "Anxious Face with Sweat"
            },
            {
                "emoji": "😥",
                "title": "Sad but Relieved Face"
            },
            {
                "emoji": "😢",
                "title": "Crying Face"
            },
            {
                "emoji": "😭",
                "title": "Loudly Crying Face"
            },
            {
                "emoji": "😱",
                "title": "Face Screaming in Fear"
            },
            {
                "emoji": "😖",
                "title": "Confounded Face"
            },
            {
                "emoji": "😣",
                "title": "Persevering Face"
            },
            {
                "emoji": "😞",
                "title": "Disappointed Face"
            },
            {
                "emoji": "😓",
                "title": "Downcast Face with Sweat"
            },
            {
                "emoji": "😩",
                "title": "Weary Face"
            },
            {
                "emoji": "😫",
                "title": "Tired Face"
            },
            {
                "emoji": "🥱",
                "title": "Yawning Face"
            },
            {
                "emoji": "😤",
                "title": "Face with Steam From Nose"
            },
            {
                "emoji": "😡",
                "title": "Enraged Face"
            },
            {
                "emoji": "😠",
                "title": "Angry Face"
            },
            {
                "emoji": "🤬",
                "title": "Face with Symbols on Mouth"
            },
            {
                "emoji": "😈",
                "title": "Smiling Face with Horns"
            },
            {
                "emoji": "👿",
                "title": "Angry Face with Horns"
            },
            {
                "emoji": "💀",
                "title": "Skull"
            },
            {
                "emoji": "☠️",
                "title": "Skull and Crossbones"
            },
            {
                "emoji": "💩",
                "title": "Pile of Poo"
            },
            {
                "emoji": "🤡",
                "title": "Clown Face"
            },
            {
                "emoji": "👹",
                "title": "Ogre"
            },
            {
                "emoji": "👺",
                "title": "Goblin"
            },
            {
                "emoji": "👻",
                "title": "Ghost"
            },
            {
                "emoji": "👽",
                "title": "Alien"
            },
            {
                "emoji": "👾",
                "title": "Alien Monster"
            },
            {
                "emoji": "🤖",
                "title": "Robot"
            },
            {
                "emoji": "😺",
                "title": "Grinning Cat"
            },
            {
                "emoji": "😸",
                "title": "Grinning Cat with Smiling Eyes"
            },
            {
                "emoji": "😹",
                "title": "Cat with Tears of Joy"
            },
            {
                "emoji": "😻",
                "title": "Smiling Cat with Heart-Eyes"
            },
            {
                "emoji": "😼",
                "title": "Cat with Wry Smile"
            },
            {
                "emoji": "😽",
                "title": "Kissing Cat"
            },
            {
                "emoji": "🙀",
                "title": "Weary Cat"
            },
            {
                "emoji": "😿",
                "title": "Crying Cat"
            },
            {
                "emoji": "😾",
                "title": "Pouting Cat"
            },
            {
                "emoji": "💋",
                "title": "Kiss Mark"
            },
            {
                "emoji": "👋",
                "title": "Waving Hand"
            },
            {
                "emoji": "🤚",
                "title": "Raised Back of Hand"
            },
            {
                "emoji": "🖐️",
                "title": "Hand with Fingers Splayed"
            },
            {
                "emoji": "✋",
                "title": "Raised Hand"
            },
            {
                "emoji": "🖖",
                "title": "Vulcan Salute"
            },
            {
                "emoji": "👌",
                "title": "OK Hand"
            },
            {
                "emoji": "🤌",
                "title": "Pinched Fingers"
            },
            {
                "emoji": "🤏",
                "title": "Pinching Hand"
            },
            {
                "emoji": "✌️",
                "title": "Victory Hand"
            },
            {
                "emoji": "🤞",
                "title": "Crossed Fingers"
            },
            {
                "emoji": "🤟",
                "title": "Love-You Gesture"
            },
            {
                "emoji": "🤘",
                "title": "Sign of the Horns"
            },
            {
                "emoji": "🤙",
                "title": "Call Me Hand"
            },
            {
                "emoji": "👈",
                "title": "Backhand Index Pointing Left"
            },
            {
                "emoji": "👉",
                "title": "Backhand Index Pointing Right"
            },
            {
                "emoji": "👆",
                "title": "Backhand Index Pointing Up"
            },
            {
                "emoji": "🖕",
                "title": "Middle Finger"
            },
            {
                "emoji": "👇",
                "title": "Backhand Index Pointing Down"
            },
            {
                "emoji": "☝️",
                "title": "Index Pointing Up"
            },
            {
                "emoji": "👍",
                "title": "Thumbs Up"
            },
            {
                "emoji": "👎",
                "title": "Thumbs Down"
            },
            {
                "emoji": "✊",
                "title": "Raised Fist"
            },
            {
                "emoji": "👊",
                "title": "Oncoming Fist"
            },
            {
                "emoji": "🤛",
                "title": "Left-Facing Fist"
            },
            {
                "emoji": "🤜",
                "title": "Right-Facing Fist"
            },
            {
                "emoji": "👏",
                "title": "Clapping Hands"
            },
            {
                "emoji": "🙌",
                "title": "Raising Hands"
            },
            {
                "emoji": "👐",
                "title": "Open Hands"
            },
            {
                "emoji": "🤲",
                "title": "Palms Up Together"
            },
            {
                "emoji": "🤝",
                "title": "Handshake"
            },
            {
                "emoji": "🙏",
                "title": "Folded Hands"
            },
            {
                "emoji": "✍️",
                "title": "Writing Hand"
            },
            {
                "emoji": "💅",
                "title": "Nail Polish"
            },
            {
                "emoji": "🤳",
                "title": "Selfie"
            },
            {
                "emoji": "💪",
                "title": "Flexed Biceps"
            },
            {
                "emoji": "🦾",
                "title": "Mechanical Arm"
            },
            {
                "emoji": "🦿",
                "title": "Mechanical Leg"
            },
            {
                "emoji": "🦵",
                "title": "Leg"
            },
            {
                "emoji": "🦶",
                "title": "Foot"
            },
            {
                "emoji": "👂",
                "title": "Ear"
            },
            {
                "emoji": "🦻",
                "title": "Ear with Hearing Aid"
            },
            {
                "emoji": "👃",
                "title": "Nose"
            },
            {
                "emoji": "🧠",
                "title": "Brain"
            },
            {
                "emoji": "🫀",
                "title": "Anatomical Heart"
            },
            {
                "emoji": "🫁",
                "title": "Lungs"
            },
            {
                "emoji": "🦷",
                "title": "Tooth"
            },
            {
                "emoji": "🦴",
                "title": "Bone"
            },
            {
                "emoji": "👀",
                "title": "Eyes"
            },
            {
                "emoji": "👁️",
                "title": "Eye"
            },
            {
                "emoji": "👅",
                "title": "Tongue"
            },
            {
                "emoji": "👄",
                "title": "Mouth"
            },
            {
                "emoji": "👶",
                "title": "Baby"
            },
            {
                "emoji": "🧒",
                "title": "Child"
            },
            {
                "emoji": "👦",
                "title": "Boy"
            },
            {
                "emoji": "👧",
                "title": "Girl"
            },
            {
                "emoji": "🧑",
                "title": "Person"
            },
            {
                "emoji": "👱",
                "title": "Person: Blond Hair"
            },
            {
                "emoji": "👨",
                "title": "Man"
            },
            {
                "emoji": "🧔",
                "title": "Person: Beard"
            },
            {
                "emoji": "👨‍🦰",
                "title": "Man: Red Hair"
            },
            {
                "emoji": "👨‍🦱",
                "title": "Man: Curly Hair"
            },
            {
                "emoji": "👨‍🦳",
                "title": "Man: White Hair"
            },
            {
                "emoji": "👨‍🦲",
                "title": "Man: Bald"
            },
            {
                "emoji": "👩",
                "title": "Woman"
            },
            {
                "emoji": "👩‍🦰",
                "title": "Woman: Red Hair"
            },
            {
                "emoji": "🧑‍🦰",
                "title": "Person: Red Hair"
            },
            {
                "emoji": "👩‍🦱",
                "title": "Woman: Curly Hair"
            },
            {
                "emoji": "🧑‍🦱",
                "title": "Person: Curly Hair"
            },
            {
                "emoji": "👩‍🦳",
                "title": "Woman: White Hair"
            },
            {
                "emoji": "🧑‍🦳",
                "title": "Person: White Hair"
            },
            {
                "emoji": "👩‍🦲",
                "title": "Woman: Bald"
            },
            {
                "emoji": "🧑‍🦲",
                "title": "Person: Bald"
            },
            {
                "emoji": "👱‍♀️",
                "title": "Woman: Blond Hair"
            },
            {
                "emoji": "👱‍♂️",
                "title": "Man: Blond Hair"
            },
            {
                "emoji": "🧓",
                "title": "Older Person"
            },
            {
                "emoji": "👴",
                "title": "Old Man"
            },
            {
                "emoji": "👵",
                "title": "Old Woman"
            },
            {
                "emoji": "🙍",
                "title": "Person Frowning"
            },
            {
                "emoji": "🙍‍♂️",
                "title": "Man Frowning"
            },
            {
                "emoji": "🙍‍♀️",
                "title": "Woman Frowning"
            },
            {
                "emoji": "🙎",
                "title": "Person Pouting"
            },
            {
                "emoji": "🙎‍♂️",
                "title": "Man Pouting"
            },
            {
                "emoji": "🙎‍♀️",
                "title": "Woman Pouting"
            },
            {
                "emoji": "🙅",
                "title": "Person Gesturing No"
            },
            {
                "emoji": "🙅‍♂️",
                "title": "Man Gesturing No"
            },
            {
                "emoji": "🙅‍♀️",
                "title": "Woman Gesturing No"
            },
            {
                "emoji": "🙆",
                "title": "Person Gesturing OK"
            },
            {
                "emoji": "🙆‍♂️",
                "title": "Man Gesturing OK"
            },
            {
                "emoji": "🙆‍♀️",
                "title": "Woman Gesturing OK"
            },
            {
                "emoji": "💁",
                "title": "Person Tipping Hand"
            },
            {
                "emoji": "💁‍♂️",
                "title": "Man Tipping Hand"
            },
            {
                "emoji": "💁‍♀️",
                "title": "Woman Tipping Hand"
            },
            {
                "emoji": "🙋",
                "title": "Person Raising Hand"
            },
            {
                "emoji": "🙋‍♂️",
                "title": "Man Raising Hand"
            },
            {
                "emoji": "🙋‍♀️",
                "title": "Woman Raising Hand"
            },
            {
                "emoji": "🧏",
                "title": "Deaf Person"
            },
            {
                "emoji": "🧏‍♂️",
                "title": "Deaf Man"
            },
            {
                "emoji": "🧏‍♀️",
                "title": "Deaf Woman"
            },
            {
                "emoji": "🙇",
                "title": "Person Bowing"
            },
            {
                "emoji": "🙇‍♂️",
                "title": "Man Bowing"
            },
            {
                "emoji": "🙇‍♀️",
                "title": "Woman Bowing"
            },
            {
                "emoji": "🤦",
                "title": "Person Facepalming"
            },
            {
                "emoji": "🤦‍♂️",
                "title": "Man Facepalming"
            },
            {
                "emoji": "🤦‍♀️",
                "title": "Woman Facepalming"
            },
            {
                "emoji": "🤷",
                "title": "Person Shrugging"
            },
            {
                "emoji": "🤷‍♂️",
                "title": "Man Shrugging"
            },
            {
                "emoji": "🤷‍♀️",
                "title": "Woman Shrugging"
            },
            {
                "emoji": "🧑‍⚕️",
                "title": "Health Worker"
            },
            {
                "emoji": "👨‍⚕️",
                "title": "Man Health Worker"
            },
            {
                "emoji": "👩‍⚕️",
                "title": "Woman Health Worker"
            },
            {
                "emoji": "🧑‍🎓",
                "title": "Student"
            },
            {
                "emoji": "👨‍🎓",
                "title": "Man Student"
            },
            {
                "emoji": "👩‍🎓",
                "title": "Woman Student"
            },
            {
                "emoji": "🧑‍🏫",
                "title": "Teacher"
            },
            {
                "emoji": "👨‍🏫",
                "title": "Man Teacher"
            },
            {
                "emoji": "👩‍🏫",
                "title": "Woman Teacher"
            },
            {
                "emoji": "🧑‍⚖️",
                "title": "Judge"
            },
            {
                "emoji": "👨‍⚖️",
                "title": "Man Judge"
            },
            {
                "emoji": "👩‍⚖️",
                "title": "Woman Judge"
            },
            {
                "emoji": "🧑‍🌾",
                "title": "Farmer"
            },
            {
                "emoji": "👨‍🌾",
                "title": "Man Farmer"
            },
            {
                "emoji": "👩‍🌾",
                "title": "Woman Farmer"
            },
            {
                "emoji": "🧑‍🍳",
                "title": "Cook"
            },
            {
                "emoji": "👨‍🍳",
                "title": "Man Cook"
            },
            {
                "emoji": "👩‍🍳",
                "title": "Woman Cook"
            },
            {
                "emoji": "🧑‍🔧",
                "title": "Mechanic"
            },
            {
                "emoji": "👨‍🔧",
                "title": "Man Mechanic"
            },
            {
                "emoji": "👩‍🔧",
                "title": "Woman Mechanic"
            },
            {
                "emoji": "🧑‍🏭",
                "title": "Factory Worker"
            },
            {
                "emoji": "👨‍🏭",
                "title": "Man Factory Worker"
            },
            {
                "emoji": "👩‍🏭",
                "title": "Woman Factory Worker"
            },
            {
                "emoji": "🧑‍💼",
                "title": "Office Worker"
            },
            {
                "emoji": "👨‍💼",
                "title": "Man Office Worker"
            },
            {
                "emoji": "👩‍💼",
                "title": "Woman Office Worker"
            },
            {
                "emoji": "🧑‍🔬",
                "title": "Scientist"
            },
            {
                "emoji": "👨‍🔬",
                "title": "Man Scientist"
            },
            {
                "emoji": "👩‍🔬",
                "title": "Woman Scientist"
            },
            {
                "emoji": "🧑‍💻",
                "title": "Technologist"
            },
            {
                "emoji": "👨‍💻",
                "title": "Man Technologist"
            },
            {
                "emoji": "👩‍💻",
                "title": "Woman Technologist"
            },
            {
                "emoji": "🧑‍🎤",
                "title": "Singer"
            },
            {
                "emoji": "👨‍🎤",
                "title": "Man Singer"
            },
            {
                "emoji": "👩‍🎤",
                "title": "Woman Singer"
            },
            {
                "emoji": "🧑‍🎨",
                "title": "Artist"
            },
            {
                "emoji": "👨‍🎨",
                "title": "Man Artist"
            },
            {
                "emoji": "👩‍🎨",
                "title": "Woman Artist"
            },
            {
                "emoji": "🧑‍✈️",
                "title": "Pilot"
            },
            {
                "emoji": "👨‍✈️",
                "title": "Man Pilot"
            },
            {
                "emoji": "👩‍✈️",
                "title": "Woman Pilot"
            },
            {
                "emoji": "🧑‍🚀",
                "title": "Astronaut"
            },
            {
                "emoji": "👨‍🚀",
                "title": "Man Astronaut"
            },
            {
                "emoji": "👩‍🚀",
                "title": "Woman Astronaut"
            },
            {
                "emoji": "🧑‍🚒",
                "title": "Firefighter"
            },
            {
                "emoji": "👨‍🚒",
                "title": "Man Firefighter"
            },
            {
                "emoji": "👩‍🚒",
                "title": "Woman Firefighter"
            },
            {
                "emoji": "👮",
                "title": "Police Officer"
            },
            {
                "emoji": "👮‍♂️",
                "title": "Man Police Officer"
            },
            {
                "emoji": "👮‍♀️",
                "title": "Woman Police Officer"
            },
            {
                "emoji": "🕵️",
                "title": "Detective"
            },
            {
                "emoji": "🕵️‍♂️",
                "title": "Man Detective"
            },
            {
                "emoji": "🕵️‍♀️",
                "title": "Woman Detective"
            },
            {
                "emoji": "💂",
                "title": "Guard"
            },
            {
                "emoji": "💂‍♂️",
                "title": "Man Guard"
            },
            {
                "emoji": "💂‍♀️",
                "title": "Woman Guard"
            },
            {
                "emoji": "🥷",
                "title": "Ninja"
            },
            {
                "emoji": "👷",
                "title": "Construction Worker"
            },
            {
                "emoji": "👷‍♂️",
                "title": "Man Construction Worker"
            },
            {
                "emoji": "👷‍♀️",
                "title": "Woman Construction Worker"
            },
            {
                "emoji": "🤴",
                "title": "Prince"
            },
            {
                "emoji": "👸",
                "title": "Princess"
            },
            {
                "emoji": "👳",
                "title": "Person Wearing Turban"
            },
            {
                "emoji": "👳‍♂️",
                "title": "Man Wearing Turban"
            },
            {
                "emoji": "👳‍♀️",
                "title": "Woman Wearing Turban"
            },
            {
                "emoji": "👲",
                "title": "Person with Skullcap"
            },
            {
                "emoji": "🧕",
                "title": "Woman with Headscarf"
            },
            {
                "emoji": "🤵",
                "title": "Person in Tuxedo"
            },
            {
                "emoji": "🤵‍♂️",
                "title": "Man in Tuxedo"
            },
            {
                "emoji": "🤵‍♀️",
                "title": "Woman in Tuxedo"
            },
            {
                "emoji": "👰",
                "title": "Person with Veil"
            },
            {
                "emoji": "👰‍♂️",
                "title": "Man with Veil"
            },
            {
                "emoji": "👰‍♀️",
                "title": "Woman with Veil"
            },
            {
                "emoji": "🤰",
                "title": "Pregnant Woman"
            },
            {
                "emoji": "🤱",
                "title": "Breast-Feeding"
            },
            {
                "emoji": "👩‍🍼",
                "title": "Woman Feeding Baby"
            },
            {
                "emoji": "👨‍🍼",
                "title": "Man Feeding Baby"
            },
            {
                "emoji": "🧑‍🍼",
                "title": "Person Feeding Baby"
            },
            {
                "emoji": "👼",
                "title": "Baby Angel"
            },
            {
                "emoji": "🎅",
                "title": "Santa Claus"
            },
            {
                "emoji": "🤶",
                "title": "Mrs. Claus"
            },
            {
                "emoji": "🧑‍🎄",
                "title": "Mx Claus"
            },
            {
                "emoji": "🦸",
                "title": "Superhero"
            },
            {
                "emoji": "🦸‍♂️",
                "title": "Man Superhero"
            },
            {
                "emoji": "🦸‍♀️",
                "title": "Woman Superhero"
            },
            {
                "emoji": "🦹",
                "title": "Supervillain"
            },
            {
                "emoji": "🦹‍♂️",
                "title": "Man Supervillain"
            },
            {
                "emoji": "🦹‍♀️",
                "title": "Woman Supervillain"
            },
            {
                "emoji": "🧙",
                "title": "Mage"
            },
            {
                "emoji": "🧙‍♂️",
                "title": "Man Mage"
            },
            {
                "emoji": "🧙‍♀️",
                "title": "Woman Mage"
            },
            {
                "emoji": "🧚",
                "title": "Fairy"
            },
            {
                "emoji": "🧚‍♂️",
                "title": "Man Fairy"
            },
            {
                "emoji": "🧚‍♀️",
                "title": "Woman Fairy"
            },
            {
                "emoji": "🧛",
                "title": "Vampire"
            },
            {
                "emoji": "🧛‍♂️",
                "title": "Man Vampire"
            },
            {
                "emoji": "🧛‍♀️",
                "title": "Woman Vampire"
            },
            {
                "emoji": "🧜",
                "title": "Merperson"
            },
            {
                "emoji": "🧜‍♂️",
                "title": "Merman"
            },
            {
                "emoji": "🧜‍♀️",
                "title": "Mermaid"
            },
            {
                "emoji": "🧝",
                "title": "Elf"
            },
            {
                "emoji": "🧝‍♂️",
                "title": "Man Elf"
            },
            {
                "emoji": "🧝‍♀️",
                "title": "Woman Elf"
            },
            {
                "emoji": "🧞",
                "title": "Genie"
            },
            {
                "emoji": "🧞‍♂️",
                "title": "Man Genie"
            },
            {
                "emoji": "🧞‍♀️",
                "title": "Woman Genie"
            },
            {
                "emoji": "🧟",
                "title": "Zombie"
            },
            {
                "emoji": "🧟‍♂️",
                "title": "Man Zombie"
            },
            {
                "emoji": "🧟‍♀️",
                "title": "Woman Zombie"
            },
            {
                "emoji": "💆",
                "title": "Person Getting Massage"
            },
            {
                "emoji": "💆‍♂️",
                "title": "Man Getting Massage"
            },
            {
                "emoji": "💆‍♀️",
                "title": "Woman Getting Massage"
            },
            {
                "emoji": "💇",
                "title": "Person Getting Haircut"
            },
            {
                "emoji": "💇‍♂️",
                "title": "Man Getting Haircut"
            },
            {
                "emoji": "💇‍♀️",
                "title": "Woman Getting Haircut"
            },
            {
                "emoji": "🚶",
                "title": "Person Walking"
            },
            {
                "emoji": "🚶‍♂️",
                "title": "Man Walking"
            },
            {
                "emoji": "🚶‍♀️",
                "title": "Woman Walking"
            },
            {
                "emoji": "🧍",
                "title": "Person Standing"
            },
            {
                "emoji": "🧍‍♂️",
                "title": "Man Standing"
            },
            {
                "emoji": "🧍‍♀️",
                "title": "Woman Standing"
            },
            {
                "emoji": "🧎",
                "title": "Person Kneeling"
            },
            {
                "emoji": "🧎‍♂️",
                "title": "Man Kneeling"
            },
            {
                "emoji": "🧎‍♀️",
                "title": "Woman Kneeling"
            },
            {
                "emoji": "🧑‍🦯",
                "title": "Person with White Cane"
            },
            {
                "emoji": "👨‍🦯",
                "title": "Man with White Cane"
            },
            {
                "emoji": "👩‍🦯",
                "title": "Woman with White Cane"
            },
            {
                "emoji": "🧑‍🦼",
                "title": "Person in Motorized Wheelchair"
            },
            {
                "emoji": "👨‍🦼",
                "title": "Man in Motorized Wheelchair"
            },
            {
                "emoji": "👩‍🦼",
                "title": "Woman in Motorized Wheelchair"
            },
            {
                "emoji": "🧑‍🦽",
                "title": "Person in Manual Wheelchair"
            },
            {
                "emoji": "👨‍🦽",
                "title": "Man in Manual Wheelchair"
            },
            {
                "emoji": "👩‍🦽",
                "title": "Woman in Manual Wheelchair"
            },
            {
                "emoji": "🏃",
                "title": "Person Running"
            },
            {
                "emoji": "🏃‍♂️",
                "title": "Man Running"
            },
            {
                "emoji": "🏃‍♀️",
                "title": "Woman Running"
            },
            {
                "emoji": "💃",
                "title": "Woman Dancing"
            },
            {
                "emoji": "🕺",
                "title": "Man Dancing"
            },
            {
                "emoji": "🕴️",
                "title": "Person in Suit Levitating"
            },
            {
                "emoji": "👯",
                "title": "People with Bunny Ears"
            },
            {
                "emoji": "👯‍♂️",
                "title": "Men with Bunny Ears"
            },
            {
                "emoji": "👯‍♀️",
                "title": "Women with Bunny Ears"
            },
            {
                "emoji": "🧖",
                "title": "Person in Steamy Room"
            },
            {
                "emoji": "🧖‍♂️",
                "title": "Man in Steamy Room"
            },
            {
                "emoji": "🧖‍♀️",
                "title": "Woman in Steamy Room"
            },
            {
                "emoji": "🧘",
                "title": "Person in Lotus Position"
            },
            {
                "emoji": "🧑‍🤝‍🧑",
                "title": "People Holding Hands"
            },
            {
                "emoji": "👭",
                "title": "Women Holding Hands"
            },
            {
                "emoji": "👫",
                "title": "Woman and Man Holding Hands"
            },
            {
                "emoji": "👬",
                "title": "Men Holding Hands"
            },
            {
                "emoji": "💏",
                "title": "Kiss"
            },
            {
                "emoji": "👩‍❤️‍💋‍👨",
                "title": "Kiss: Woman, Man"
            },
            {
                "emoji": "👨‍❤️‍💋‍👨",
                "title": "Kiss: Man, Man"
            },
            {
                "emoji": "👩‍❤️‍💋‍👩",
                "title": "Kiss: Woman, Woman"
            },
            {
                "emoji": "💑",
                "title": "Couple with Heart"
            },
            {
                "emoji": "👩‍❤️‍👨",
                "title": "Couple with Heart: Woman, Man"
            },
            {
                "emoji": "👨‍❤️‍👨",
                "title": "Couple with Heart: Man, Man"
            },
            {
                "emoji": "👩‍❤️‍👩",
                "title": "Couple with Heart: Woman, Woman"
            },
            {
                "emoji": "👪",
                "title": "Family"
            },
            {
                "emoji": "👨‍👩‍👦",
                "title": "Family: Man, Woman, Boy"
            },
            {
                "emoji": "👨‍👩‍👧",
                "title": "Family: Man, Woman, Girl"
            },
            {
                "emoji": "👨‍👩‍👧‍👦",
                "title": "Family: Man, Woman, Girl, Boy"
            },
            {
                "emoji": "👨‍👩‍👦‍👦",
                "title": "Family: Man, Woman, Boy, Boy"
            },
            {
                "emoji": "👨‍👩‍👧‍👧",
                "title": "Family: Man, Woman, Girl, Girl"
            },
            {
                "emoji": "👨‍👨‍👦",
                "title": "Family: Man, Man, Boy"
            },
            {
                "emoji": "👨‍👨‍👧",
                "title": "Family: Man, Man, Girl"
            },
            {
                "emoji": "👨‍👨‍👧‍👦",
                "title": "Family: Man, Man, Girl, Boy"
            },
            {
                "emoji": "👨‍👨‍👦‍👦",
                "title": "Family: Man, Man, Boy, Boy"
            },
            {
                "emoji": "👨‍👨‍👧‍👧",
                "title": "Family: Man, Man, Girl, Girl"
            },
            {
                "emoji": "👩‍👩‍👦",
                "title": "Family: Woman, Woman, Boy"
            },
            {
                "emoji": "👩‍👩‍👧",
                "title": "Family: Woman, Woman, Girl"
            },
            {
                "emoji": "👩‍👩‍👧‍👦",
                "title": "Family: Woman, Woman, Girl, Boy"
            },
            {
                "emoji": "👩‍👩‍👦‍👦",
                "title": "Family: Woman, Woman, Boy, Boy"
            },
            {
                "emoji": "👩‍👩‍👧‍👧",
                "title": "Family: Woman, Woman, Girl, Girl"
            },
            {
                "emoji": "👨‍👦",
                "title": "Family: Man, Boy"
            },
            {
                "emoji": "👨‍👦‍👦",
                "title": "Family: Man, Boy, Boy"
            },
            {
                "emoji": "👨‍👧",
                "title": "Family: Man, Girl"
            },
            {
                "emoji": "👨‍👧‍👦",
                "title": "Family: Man, Girl, Boy"
            },
            {
                "emoji": "👨‍👧‍👧",
                "title": "Family: Man, Girl, Girl"
            },
            {
                "emoji": "👩‍👦",
                "title": "Family: Woman, Boy"
            },
            {
                "emoji": "👩‍👦‍👦",
                "title": "Family: Woman, Boy, Boy"
            },
            {
                "emoji": "👩‍👧",
                "title": "Family: Woman, Girl"
            },
            {
                "emoji": "👩‍👧‍👦",
                "title": "Family: Woman, Girl, Boy"
            },
            {
                "emoji": "👩‍👧‍👧",
                "title": "Family: Woman, Girl, Girl"
            },
            {
                "emoji": "🗣️",
                "title": "Speaking Head"
            },
            {
                "emoji": "👤",
                "title": "Bust in Silhouette"
            },
            {
                "emoji": "👥",
                "title": "Busts in Silhouette"
            },
            {
                "emoji": "🫂",
                "title": "People Hugging"
            },
            {
                "emoji": "👣",
                "title": "Footprints"
            },
            {
                "emoji": "🧳",
                "title": "Luggage"
            },
            {
                "emoji": "🌂",
                "title": "Closed Umbrella"
            },
            {
                "emoji": "☂️",
                "title": "Umbrella"
            },
            {
                "emoji": "🎃",
                "title": "Jack-O-Lantern"
            },
            {
                "emoji": "🧵",
                "title": "Thread"
            },
            {
                "emoji": "🧶",
                "title": "Yarn"
            },
            {
                "emoji": "👓",
                "title": "Glasses"
            },
            {
                "emoji": "🕶️",
                "title": "Sunglasses"
            },
            {
                "emoji": "🥽",
                "title": "Goggles"
            },
            {
                "emoji": "🥼",
                "title": "Lab Coat"
            },
            {
                "emoji": "🦺",
                "title": "Safety Vest"
            },
            {
                "emoji": "👔",
                "title": "Necktie"
            },
            {
                "emoji": "👕",
                "title": "T-Shirt"
            },
            {
                "emoji": "👖",
                "title": "Jeans"
            },
            {
                "emoji": "🧣",
                "title": "Scarf"
            },
            {
                "emoji": "🧤",
                "title": "Gloves"
            },
            {
                "emoji": "🧥",
                "title": "Coat"
            },
            {
                "emoji": "🧦",
                "title": "Socks"
            },
            {
                "emoji": "👗",
                "title": "Dress"
            },
            {
                "emoji": "👘",
                "title": "Kimono"
            },
            {
                "emoji": "🥻",
                "title": "Sari"
            },
            {
                "emoji": "🩱",
                "title": "One-Piece Swimsuit"
            },
            {
                "emoji": "🩲",
                "title": "Briefs"
            },
            {
                "emoji": "🩳",
                "title": "Shorts"
            },
            {
                "emoji": "👙",
                "title": "Bikini"
            },
            {
                "emoji": "👚",
                "title": "Woman’s Clothes"
            },
            {
                "emoji": "👛",
                "title": "Purse"
            },
            {
                "emoji": "👜",
                "title": "Handbag"
            },
            {
                "emoji": "👝",
                "title": "Clutch Bag"
            },
            {
                "emoji": "🎒",
                "title": "Backpack"
            },
            {
                "emoji": "🩴",
                "title": "Thong Sandal"
            },
            {
                "emoji": "👞",
                "title": "Man’s Shoe"
            },
            {
                "emoji": "👟",
                "title": "Running Shoe"
            },
            {
                "emoji": "🥾",
                "title": "Hiking Boot"
            },
            {
                "emoji": "🥿",
                "title": "Flat Shoe"
            },
            {
                "emoji": "👠",
                "title": "High-Heeled Shoe"
            },
            {
                "emoji": "👡",
                "title": "Woman’s Sandal"
            },
            {
                "emoji": "🩰",
                "title": "Ballet Shoes"
            },
            {
                "emoji": "👢",
                "title": "Woman’s Boot"
            },
            {
                "emoji": "👑",
                "title": "Crown"
            },
            {
                "emoji": "👒",
                "title": "Woman’s Hat"
            },
            {
                "emoji": "🎩",
                "title": "Top Hat"
            },
            {
                "emoji": "🎓",
                "title": "Graduation Cap"
            },
            {
                "emoji": "🧢",
                "title": "Billed Cap"
            },
            {
                "emoji": "🪖",
                "title": "Military Helmet"
            },
            {
                "emoji": "⛑️",
                "title": "Rescue Worker’s Helmet"
            },
            {
                "emoji": "💄",
                "title": "Lipstick"
            },
            {
                "emoji": "💍",
                "title": "Ring"
            },
            {
                "emoji": "💼",
                "title": "Briefcase"
            },
            {
                "emoji": "🩸",
                "title": "Drop of Blood"
            }
        ],
        'Nature': [
            {
                "emoji": "🙈",
                "title": "See-No-Evil Monkey"
            },
            {
                "emoji": "🙉",
                "title": "Hear-No-Evil Monkey"
            },
            {
                "emoji": "🙊",
                "title": "Speak-No-Evil Monkey"
            },
            {
                "emoji": "💥",
                "title": "Collision"
            },
            {
                "emoji": "💫",
                "title": "Dizzy"
            },
            {
                "emoji": "💦",
                "title": "Sweat Droplets"
            },
            {
                "emoji": "💨",
                "title": "Dashing Away"
            },
            {
                "emoji": "🐵",
                "title": "Monkey Face"
            },
            {
                "emoji": "🐒",
                "title": "Monkey"
            },
            {
                "emoji": "🦍",
                "title": "Gorilla"
            },
            {
                "emoji": "🦧",
                "title": "Orangutan"
            },
            {
                "emoji": "🐶",
                "title": "Dog Face"
            },
            {
                "emoji": "🐕",
                "title": "Dog"
            },
            {
                "emoji": "🦮",
                "title": "Guide Dog"
            },
            {
                "emoji": "🐕‍🦺",
                "title": "Service Dog"
            },
            {
                "emoji": "🐩",
                "title": "Poodle"
            },
            {
                "emoji": "🐺",
                "title": "Wolf"
            },
            {
                "emoji": "🦊",
                "title": "Fox"
            },
            {
                "emoji": "🦝",
                "title": "Raccoon"
            },
            {
                "emoji": "🐱",
                "title": "Cat Face"
            },
            {
                "emoji": "🐈",
                "title": "Cat"
            },
            {
                "emoji": "🐈‍⬛",
                "title": "Black Cat"
            },
            {
                "emoji": "🦁",
                "title": "Lion"
            },
            {
                "emoji": "🐯",
                "title": "Tiger Face"
            },
            {
                "emoji": "🐅",
                "title": "Tiger"
            },
            {
                "emoji": "🐆",
                "title": "Leopard"
            },
            {
                "emoji": "🐴",
                "title": "Horse Face"
            },
            {
                "emoji": "🐎",
                "title": "Horse"
            },
            {
                "emoji": "🦄",
                "title": "Unicorn"
            },
            {
                "emoji": "🦓",
                "title": "Zebra"
            },
            {
                "emoji": "🦌",
                "title": "Deer"
            },
            {
                "emoji": "🦬",
                "title": "Bison"
            },
            {
                "emoji": "🐮",
                "title": "Cow Face"
            },
            {
                "emoji": "🐂",
                "title": "Ox"
            },
            {
                "emoji": "🐃",
                "title": "Water Buffalo"
            },
            {
                "emoji": "🐄",
                "title": "Cow"
            },
            {
                "emoji": "🐷",
                "title": "Pig Face"
            },
            {
                "emoji": "🐖",
                "title": "Pig"
            },
            {
                "emoji": "🐗",
                "title": "Boar"
            },
            {
                "emoji": "🐽",
                "title": "Pig Nose"
            },
            {
                "emoji": "🐏",
                "title": "Ram"
            },
            {
                "emoji": "🐑",
                "title": "Ewe"
            },
            {
                "emoji": "🐐",
                "title": "Goat"
            },
            {
                "emoji": "🐪",
                "title": "Camel"
            },
            {
                "emoji": "🐫",
                "title": "Two-Hump Camel"
            },
            {
                "emoji": "🦙",
                "title": "Llama"
            },
            {
                "emoji": "🦒",
                "title": "Giraffe"
            },
            {
                "emoji": "🐘",
                "title": "Elephant"
            },
            {
                "emoji": "🦣",
                "title": "Mammoth"
            },
            {
                "emoji": "🦏",
                "title": "Rhinoceros"
            },
            {
                "emoji": "🦛",
                "title": "Hippopotamus"
            },
            {
                "emoji": "🐭",
                "title": "Mouse Face"
            },
            {
                "emoji": "🐁",
                "title": "Mouse"
            },
            {
                "emoji": "🐀",
                "title": "Rat"
            },
            {
                "emoji": "🐹",
                "title": "Hamster"
            },
            {
                "emoji": "🐰",
                "title": "Rabbit Face"
            },
            {
                "emoji": "🐇",
                "title": "Rabbit"
            },
            {
                "emoji": "🐿️",
                "title": "Chipmunk"
            },
            {
                "emoji": "🦫",
                "title": "Beaver"
            },
            {
                "emoji": "🦔",
                "title": "Hedgehog"
            },
            {
                "emoji": "🦇",
                "title": "Bat"
            },
            {
                "emoji": "🐻",
                "title": "Bear"
            },
            {
                "emoji": "🐻‍❄️",
                "title": "Polar Bear"
            },
            {
                "emoji": "🐨",
                "title": "Koala"
            },
            {
                "emoji": "🐼",
                "title": "Panda"
            },
            {
                "emoji": "🦥",
                "title": "Sloth"
            },
            {
                "emoji": "🦦",
                "title": "Otter"
            },
            {
                "emoji": "🦨",
                "title": "Skunk"
            },
            {
                "emoji": "🦘",
                "title": "Kangaroo"
            },
            {
                "emoji": "🦡",
                "title": "Badger"
            },
            {
                "emoji": "🐾",
                "title": "Paw Prints"
            },
            {
                "emoji": "🦃",
                "title": "Turkey"
            },
            {
                "emoji": "🐔",
                "title": "Chicken"
            },
            {
                "emoji": "🐓",
                "title": "Rooster"
            },
            {
                "emoji": "🐣",
                "title": "Hatching Chick"
            },
            {
                "emoji": "🐤",
                "title": "Baby Chick"
            },
            {
                "emoji": "🐥",
                "title": "Front-Facing Baby Chick"
            },
            {
                "emoji": "🐦",
                "title": "Bird"
            },
            {
                "emoji": "🐧",
                "title": "Penguin"
            },
            {
                "emoji": "🕊️",
                "title": "Dove"
            },
            {
                "emoji": "🦅",
                "title": "Eagle"
            },
            {
                "emoji": "🦆",
                "title": "Duck"
            },
            {
                "emoji": "🦢",
                "title": "Swan"
            },
            {
                "emoji": "🦉",
                "title": "Owl"
            },
            {
                "emoji": "🦤",
                "title": "Dodo"
            },
            {
                "emoji": "🪶",
                "title": "Feather"
            },
            {
                "emoji": "🦩",
                "title": "Flamingo"
            },
            {
                "emoji": "🦚",
                "title": "Peacock"
            },
            {
                "emoji": "🦜",
                "title": "Parrot"
            },
            {
                "emoji": "🐸",
                "title": "Frog"
            },
            {
                "emoji": "🐊",
                "title": "Crocodile"
            },
            {
                "emoji": "🐢",
                "title": "Turtle"
            },
            {
                "emoji": "🦎",
                "title": "Lizard"
            },
            {
                "emoji": "🐍",
                "title": "Snake"
            },
            {
                "emoji": "🐲",
                "title": "Dragon Face"
            },
            {
                "emoji": "🐉",
                "title": "Dragon"
            },
            {
                "emoji": "🦕",
                "title": "Sauropod"
            },
            {
                "emoji": "🦖",
                "title": "T-Rex"
            },
            {
                "emoji": "🐳",
                "title": "Spouting Whale"
            },
            {
                "emoji": "🐋",
                "title": "Whale"
            },
            {
                "emoji": "🐬",
                "title": "Dolphin"
            },
            {
                "emoji": "🦭",
                "title": "Seal"
            },
            {
                "emoji": "🐟",
                "title": "Fish"
            },
            {
                "emoji": "🐠",
                "title": "Tropical Fish"
            },
            {
                "emoji": "🐡",
                "title": "Blowfish"
            },
            {
                "emoji": "🦈",
                "title": "Shark"
            },
            {
                "emoji": "🐙",
                "title": "Octopus"
            },
            {
                "emoji": "🐚",
                "title": "Spiral Shell"
            },
            {
                "emoji": "🐌",
                "title": "Snail"
            },
            {
                "emoji": "🦋",
                "title": "Butterfly"
            },
            {
                "emoji": "🐛",
                "title": "Bug"
            },
            {
                "emoji": "🐜",
                "title": "Ant"
            },
            {
                "emoji": "🐝",
                "title": "Honeybee"
            },
            {
                "emoji": "🪲",
                "title": "Beetle"
            },
            {
                "emoji": "🐞",
                "title": "Lady Beetle"
            },
            {
                "emoji": "🦗",
                "title": "Cricket"
            },
            {
                "emoji": "🪳",
                "title": "Cockroach"
            },
            {
                "emoji": "🕷️",
                "title": "Spider"
            },
            {
                "emoji": "🕸️",
                "title": "Spider Web"
            },
            {
                "emoji": "🦂",
                "title": "Scorpion"
            },
            {
                "emoji": "🦟",
                "title": "Mosquito"
            },
            {
                "emoji": "🪰",
                "title": "Fly"
            },
            {
                "emoji": "🪱",
                "title": "Worm"
            },
            {
                "emoji": "🦠",
                "title": "Microbe"
            },
            {
                "emoji": "💐",
                "title": "Bouquet"
            },
            {
                "emoji": "🌸",
                "title": "Cherry Blossom"
            },
            {
                "emoji": "💮",
                "title": "White Flower"
            },
            {
                "emoji": "🏵️",
                "title": "Rosette"
            },
            {
                "emoji": "🌹",
                "title": "Rose"
            },
            {
                "emoji": "🥀",
                "title": "Wilted Flower"
            },
            {
                "emoji": "🌺",
                "title": "Hibiscus"
            },
            {
                "emoji": "🌻",
                "title": "Sunflower"
            },
            {
                "emoji": "🌼",
                "title": "Blossom"
            },
            {
                "emoji": "🌷",
                "title": "Tulip"
            },
            {
                "emoji": "🌱",
                "title": "Seedling"
            },
            {
                "emoji": "🪴",
                "title": "Potted Plant"
            },
            {
                "emoji": "🌲",
                "title": "Evergreen Tree"
            },
            {
                "emoji": "🌳",
                "title": "Deciduous Tree"
            },
            {
                "emoji": "🌴",
                "title": "Palm Tree"
            },
            {
                "emoji": "🌵",
                "title": "Cactus"
            },
            {
                "emoji": "🌾",
                "title": "Sheaf of Rice"
            },
            {
                "emoji": "🌿",
                "title": "Herb"
            },
            {
                "emoji": "☘️",
                "title": "Shamrock"
            },
            {
                "emoji": "🍀",
                "title": "Four Leaf Clover"
            },
            {
                "emoji": "🍁",
                "title": "Maple Leaf"
            },
            {
                "emoji": "🍂",
                "title": "Fallen Leaf"
            },
            {
                "emoji": "🍃",
                "title": "Leaf Fluttering in Wind"
            },
            {
                "emoji": "🍄",
                "title": "Mushroom"
            },
            {
                "emoji": "🌰",
                "title": "Chestnut"
            },
            {
                "emoji": "🦀",
                "title": "Crab"
            },
            {
                "emoji": "🦞",
                "title": "Lobster"
            },
            {
                "emoji": "🦐",
                "title": "Shrimp"
            },
            {
                "emoji": "🦑",
                "title": "Squid"
            },
            {
                "emoji": "🌍",
                "title": "Globe Showing Europe-Africa"
            },
            {
                "emoji": "🌎",
                "title": "Globe Showing Americas"
            },
            {
                "emoji": "🌏",
                "title": "Globe Showing Asia-Australia"
            },
            {
                "emoji": "🌐",
                "title": "Globe with Meridians"
            },
            {
                "emoji": "🪨",
                "title": "Rock"
            },
            {
                "emoji": "🌑",
                "title": "New Moon"
            },
            {
                "emoji": "🌒",
                "title": "Waxing Crescent Moon"
            },
            {
                "emoji": "🌓",
                "title": "First Quarter Moon"
            },
            {
                "emoji": "🌔",
                "title": "Waxing Gibbous Moon"
            },
            {
                "emoji": "🌕",
                "title": "Full Moon"
            },
            {
                "emoji": "🌖",
                "title": "Waning Gibbous Moon"
            },
            {
                "emoji": "🌗",
                "title": "Last Quarter Moon"
            },
            {
                "emoji": "🌘",
                "title": "Waning Crescent Moon"
            },
            {
                "emoji": "🌙",
                "title": "Crescent Moon"
            },
            {
                "emoji": "🌚",
                "title": "New Moon Face"
            },
            {
                "emoji": "🌛",
                "title": "First Quarter Moon Face"
            },
            {
                "emoji": "🌜",
                "title": "Last Quarter Moon Face"
            },
            {
                "emoji": "☀️",
                "title": "Sun"
            },
            {
                "emoji": "🌝",
                "title": "Full Moon Face"
            },
            {
                "emoji": "🌞",
                "title": "Sun with Face"
            },
            {
                "emoji": "⭐",
                "title": "Star"
            },
            {
                "emoji": "🌟",
                "title": "Glowing Star"
            },
            {
                "emoji": "🌠",
                "title": "Shooting Star"
            },
            {
                "emoji": "☁️",
                "title": "Cloud"
            },
            {
                "emoji": "⛅",
                "title": "Sun Behind Cloud"
            },
            {
                "emoji": "⛈️",
                "title": "Cloud with Lightning and Rain"
            },
            {
                "emoji": "🌤️",
                "title": "Sun Behind Small Cloud"
            },
            {
                "emoji": "🌥️",
                "title": "Sun Behind Large Cloud"
            },
            {
                "emoji": "🌦️",
                "title": "Sun Behind Rain Cloud"
            },
            {
                "emoji": "🌧️",
                "title": "Cloud with Rain"
            },
            {
                "emoji": "🌨️",
                "title": "Cloud with Snow"
            },
            {
                "emoji": "🌩️",
                "title": "Cloud with Lightning"
            },
            {
                "emoji": "🌪️",
                "title": "Tornado"
            },
            {
                "emoji": "🌫️",
                "title": "Fog"
            },
            {
                "emoji": "🌬️",
                "title": "Wind Face"
            },
            {
                "emoji": "🌈",
                "title": "Rainbow"
            },
            {
                "emoji": "☂️",
                "title": "Umbrella"
            },
            {
                "emoji": "☔",
                "title": "Umbrella with Rain Drops"
            },
            {
                "emoji": "⚡",
                "title": "High Voltage"
            },
            {
                "emoji": "❄️",
                "title": "Snowflake"
            },
            {
                "emoji": "☃️",
                "title": "Snowman"
            },
            {
                "emoji": "⛄",
                "title": "Snowman Without Snow"
            },
            {
                "emoji": "☄️",
                "title": "Comet"
            },
            {
                "emoji": "🔥",
                "title": "Fire"
            },
            {
                "emoji": "💧",
                "title": "Droplet"
            },
            {
                "emoji": "🌊",
                "title": "Water Wave"
            },
            {
                "emoji": "🎄",
                "title": "Christmas Tree"
            },
            {
                "emoji": "✨",
                "title": "Sparkles"
            },
            {
                "emoji": "🎋",
                "title": "Tanabata Tree"
            },
            {
                "emoji": "🎍",
                "title": "Pine Decoration"
            }
        ],
        'Food-dring': [
            {
                "emoji": "🍇",
                "title": "Grapes"
            },
            {
                "emoji": "🍈",
                "title": "Melon"
            },
            {
                "emoji": "🍉",
                "title": "Watermelon"
            },
            {
                "emoji": "🍊",
                "title": "Tangerine"
            },
            {
                "emoji": "🍋",
                "title": "Lemon"
            },
            {
                "emoji": "🍌",
                "title": "Banana"
            },
            {
                "emoji": "🍍",
                "title": "Pineapple"
            },
            {
                "emoji": "🥭",
                "title": "Mango"
            },
            {
                "emoji": "🍎",
                "title": "Red Apple"
            },
            {
                "emoji": "🍏",
                "title": "Green Apple"
            },
            {
                "emoji": "🍐",
                "title": "Pear"
            },
            {
                "emoji": "🍑",
                "title": "Peach"
            },
            {
                "emoji": "🍒",
                "title": "Cherries"
            },
            {
                "emoji": "🍓",
                "title": "Strawberry"
            },
            {
                "emoji": "🫐",
                "title": "Blueberries"
            },
            {
                "emoji": "🥝",
                "title": "Kiwi Fruit"
            },
            {
                "emoji": "🍅",
                "title": "Tomato"
            },
            {
                "emoji": "🫒",
                "title": "Olive"
            },
            {
                "emoji": "🥥",
                "title": "Coconut"
            },
            {
                "emoji": "🥑",
                "title": "Avocado"
            },
            {
                "emoji": "🍆",
                "title": "Eggplant"
            },
            {
                "emoji": "🥔",
                "title": "Potato"
            },
            {
                "emoji": "🥕",
                "title": "Carrot"
            },
            {
                "emoji": "🌽",
                "title": "Ear of Corn"
            },
            {
                "emoji": "🌶️",
                "title": "Hot Pepper"
            },
            {
                "emoji": "🫑",
                "title": "Bell Pepper"
            },
            {
                "emoji": "🥒",
                "title": "Cucumber"
            },
            {
                "emoji": "🥬",
                "title": "Leafy Green"
            },
            {
                "emoji": "🥦",
                "title": "Broccoli"
            },
            {
                "emoji": "🧄",
                "title": "Garlic"
            },
            {
                "emoji": "🧅",
                "title": "Onion"
            },
            {
                "emoji": "🍄",
                "title": "Mushroom"
            },
            {
                "emoji": "🥜",
                "title": "Peanuts"
            },
            {
                "emoji": "🌰",
                "title": "Chestnut"
            },
            {
                "emoji": "🍞",
                "title": "Bread"
            },
            {
                "emoji": "🥐",
                "title": "Croissant"
            },
            {
                "emoji": "🥖",
                "title": "Baguette Bread"
            },
            {
                "emoji": "🫓",
                "title": "Flatbread"
            },
            {
                "emoji": "🥨",
                "title": "Pretzel"
            },
            {
                "emoji": "🥯",
                "title": "Bagel"
            },
            {
                "emoji": "🥞",
                "title": "Pancakes"
            },
            {
                "emoji": "🧇",
                "title": "Waffle"
            },
            {
                "emoji": "🧀",
                "title": "Cheese Wedge"
            },
            {
                "emoji": "🍖",
                "title": "Meat on Bone"
            },
            {
                "emoji": "🍗",
                "title": "Poultry Leg"
            },
            {
                "emoji": "🥩",
                "title": "Cut of Meat"
            },
            {
                "emoji": "🥓",
                "title": "Bacon"
            },
            {
                "emoji": "🍔",
                "title": "Hamburger"
            },
            {
                "emoji": "🍟",
                "title": "French Fries"
            },
            {
                "emoji": "🍕",
                "title": "Pizza"
            },
            {
                "emoji": "🌭",
                "title": "Hot Dog"
            },
            {
                "emoji": "🥪",
                "title": "Sandwich"
            },
            {
                "emoji": "🌮",
                "title": "Taco"
            },
            {
                "emoji": "🌯",
                "title": "Burrito"
            },
            {
                "emoji": "🫔",
                "title": "Tamale"
            },
            {
                "emoji": "🥙",
                "title": "Stuffed Flatbread"
            },
            {
                "emoji": "🧆",
                "title": "Falafel"
            },
            {
                "emoji": "🥚",
                "title": "Egg"
            },
            {
                "emoji": "🍳",
                "title": "Cooking"
            },
            {
                "emoji": "🥘",
                "title": "Shallow Pan of Food"
            },
            {
                "emoji": "🍲",
                "title": "Pot of Food"
            },
            {
                "emoji": "🫕",
                "title": "Fondue"
            },
            {
                "emoji": "🥣",
                "title": "Bowl with Spoon"
            },
            {
                "emoji": "🥗",
                "title": "Green Salad"
            },
            {
                "emoji": "🍿",
                "title": "Popcorn"
            },
            {
                "emoji": "🧈",
                "title": "Butter"
            },
            {
                "emoji": "🧂",
                "title": "Salt"
            },
            {
                "emoji": "🥫",
                "title": "Canned Food"
            },
            {
                "emoji": "🍱",
                "title": "Bento Box"
            },
            {
                "emoji": "🍘",
                "title": "Rice Cracker"
            },
            {
                "emoji": "🍙",
                "title": "Rice Ball"
            },
            {
                "emoji": "🍚",
                "title": "Cooked Rice"
            },
            {
                "emoji": "🍛",
                "title": "Curry Rice"
            },
            {
                "emoji": "🍜",
                "title": "Steaming Bowl"
            },
            {
                "emoji": "🍝",
                "title": "Spaghetti"
            },
            {
                "emoji": "🍠",
                "title": "Roasted Sweet Potato"
            },
            {
                "emoji": "🍢",
                "title": "Oden"
            },
            {
                "emoji": "🍣",
                "title": "Sushi"
            },
            {
                "emoji": "🍤",
                "title": "Fried Shrimp"
            },
            {
                "emoji": "🍥",
                "title": "Fish Cake with Swirl"
            },
            {
                "emoji": "🥮",
                "title": "Moon Cake"
            },
            {
                "emoji": "🍡",
                "title": "Dango"
            },
            {
                "emoji": "🥟",
                "title": "Dumpling"
            },
            {
                "emoji": "🥠",
                "title": "Fortune Cookie"
            },
            {
                "emoji": "🥡",
                "title": "Takeout Box"
            },
            {
                "emoji": "🦪",
                "title": "Oyster"
            },
            {
                "emoji": "🍦",
                "title": "Soft Ice Cream"
            },
            {
                "emoji": "🍧",
                "title": "Shaved Ice"
            },
            {
                "emoji": "🍨",
                "title": "Ice Cream"
            },
            {
                "emoji": "🍩",
                "title": "Doughnut"
            },
            {
                "emoji": "🍪",
                "title": "Cookie"
            },
            {
                "emoji": "🎂",
                "title": "Birthday Cake"
            },
            {
                "emoji": "🍰",
                "title": "Shortcake"
            },
            {
                "emoji": "🧁",
                "title": "Cupcake"
            },
            {
                "emoji": "🥧",
                "title": "Pie"
            },
            {
                "emoji": "🍫",
                "title": "Chocolate Bar"
            },
            {
                "emoji": "🍬",
                "title": "Candy"
            },
            {
                "emoji": "🍭",
                "title": "Lollipop"
            },
            {
                "emoji": "🍮",
                "title": "Custard"
            },
            {
                "emoji": "🍯",
                "title": "Honey Pot"
            },
            {
                "emoji": "🍼",
                "title": "Baby Bottle"
            },
            {
                "emoji": "🥛",
                "title": "Glass of Milk"
            },
            {
                "emoji": "☕",
                "title": "Hot Beverage"
            },
            {
                "emoji": "🫖",
                "title": "Teapot"
            },
            {
                "emoji": "🍵",
                "title": "Teacup Without Handle"
            },
            {
                "emoji": "🍶",
                "title": "Sake"
            },
            {
                "emoji": "🍾",
                "title": "Bottle with Popping Cork"
            },
            {
                "emoji": "🍷",
                "title": "Wine Glass"
            },
            {
                "emoji": "🍸",
                "title": "Cocktail Glass"
            },
            {
                "emoji": "🍹",
                "title": "Tropical Drink"
            },
            {
                "emoji": "🍺",
                "title": "Beer Mug"
            },
            {
                "emoji": "🍻",
                "title": "Clinking Beer Mugs"
            },
            {
                "emoji": "🥂",
                "title": "Clinking Glasses"
            },
            {
                "emoji": "🥃",
                "title": "Tumbler Glass"
            },
            {
                "emoji": "🥤",
                "title": "Cup with Straw"
            },
            {
                "emoji": "🧋",
                "title": "Bubble Tea"
            },
            {
                "emoji": "🧃",
                "title": "Beverage Box"
            },
            {
                "emoji": "🧉",
                "title": "Mate"
            },
            {
                "emoji": "🧊",
                "title": "Ice"
            },
            {
                "emoji": "🥢",
                "title": "Chopsticks"
            },
            {
                "emoji": "🍽️",
                "title": "Fork and Knife with Plate"
            },
            {
                "emoji": "🍴",
                "title": "Fork and Knife"
            },
            {
                "emoji": "🥄",
                "title": "Spoon"
            }
        ],
        'Activity': [
            {
                "emoji": "🕴️",
                "title": "Person in Suit Levitating"
            },
            {
                "emoji": "🧗",
                "title": "Person Climbing"
            },
            {
                "emoji": "🧗‍♂️",
                "title": "Man Climbing"
            },
            {
                "emoji": "🧗‍♀️",
                "title": "Woman Climbing"
            },
            {
                "emoji": "🤺",
                "title": "Person Fencing"
            },
            {
                "emoji": "🏇",
                "title": "Horse Racing"
            },
            {
                "emoji": "⛷️",
                "title": "Skier"
            },
            {
                "emoji": "🏂",
                "title": "Snowboarder"
            },
            {
                "emoji": "🏌️",
                "title": "Person Golfing"
            },
            {
                "emoji": "🏌️‍♂️",
                "title": "Man Golfing"
            },
            {
                "emoji": "🏌️‍♀️",
                "title": "Woman Golfing"
            },
            {
                "emoji": "🏄",
                "title": "Person Surfing"
            },
            {
                "emoji": "🏄‍♂️",
                "title": "Man Surfing"
            },
            {
                "emoji": "🏄‍♀️",
                "title": "Woman Surfing"
            },
            {
                "emoji": "🚣",
                "title": "Person Rowing Boat"
            },
            {
                "emoji": "🚣‍♂️",
                "title": "Man Rowing Boat"
            },
            {
                "emoji": "🚣‍♀️",
                "title": "Woman Rowing Boat"
            },
            {
                "emoji": "🏊",
                "title": "Person Swimming"
            },
            {
                "emoji": "🏊‍♂️",
                "title": "Man Swimming"
            },
            {
                "emoji": "🏊‍♀️",
                "title": "Woman Swimming"
            },
            {
                "emoji": "⛹️",
                "title": "Person Bouncing Ball"
            },
            {
                "emoji": "⛹️‍♂️",
                "title": "Man Bouncing Ball"
            },
            {
                "emoji": "⛹️‍♀️",
                "title": "Woman Bouncing Ball"
            },
            {
                "emoji": "🏋️",
                "title": "Person Lifting Weights"
            },
            {
                "emoji": "🏋️‍♂️",
                "title": "Man Lifting Weights"
            },
            {
                "emoji": "🏋️‍♀️",
                "title": "Woman Lifting Weights"
            },
            {
                "emoji": "🚴",
                "title": "Person Biking"
            },
            {
                "emoji": "🚴‍♂️",
                "title": "Man Biking"
            },
            {
                "emoji": "🚴‍♀️",
                "title": "Woman Biking"
            },
            {
                "emoji": "🚵",
                "title": "Person Mountain Biking"
            },
            {
                "emoji": "🚵‍♂️",
                "title": "Man Mountain Biking"
            },
            {
                "emoji": "🚵‍♀️",
                "title": "Woman Mountain Biking"
            },
            {
                "emoji": "🤸",
                "title": "Person Cartwheeling"
            },
            {
                "emoji": "🤸‍♂️",
                "title": "Man Cartwheeling"
            },
            {
                "emoji": "🤸‍♀️",
                "title": "Woman Cartwheeling"
            },
            {
                "emoji": "🤼",
                "title": "People Wrestling"
            },
            {
                "emoji": "🤼‍♂️",
                "title": "Men Wrestling"
            },
            {
                "emoji": "🤼‍♀️",
                "title": "Women Wrestling"
            },
            {
                "emoji": "🤽",
                "title": "Person Playing Water Polo"
            },
            {
                "emoji": "🤽‍♂️",
                "title": "Man Playing Water Polo"
            },
            {
                "emoji": "🤽‍♀️",
                "title": "Woman Playing Water Polo"
            },
            {
                "emoji": "🤾",
                "title": "Person Playing Handball"
            },
            {
                "emoji": "🤾‍♂️",
                "title": "Man Playing Handball"
            },
            {
                "emoji": "🤾‍♀️",
                "title": "Woman Playing Handball"
            },
            {
                "emoji": "🤹",
                "title": "Person Juggling"
            },
            {
                "emoji": "🤹‍♂️",
                "title": "Man Juggling"
            },
            {
                "emoji": "🤹‍♀️",
                "title": "Woman Juggling"
            },
            {
                "emoji": "🧘",
                "title": "Person in Lotus Position"
            },
            {
                "emoji": "🧘‍♂️",
                "title": "Man in Lotus Position"
            },
            {
                "emoji": "🧘‍♀️",
                "title": "Woman in Lotus Position"
            },
            {
                "emoji": "🎪",
                "title": "Circus Tent"
            },
            {
                "emoji": "🛹",
                "title": "Skateboard"
            },
            {
                "emoji": "🛼",
                "title": "Roller Skate"
            },
            {
                "emoji": "🛶",
                "title": "Canoe"
            },
            {
                "emoji": "🎗️",
                "title": "Reminder Ribbon"
            },
            {
                "emoji": "🎟️",
                "title": "Admission Tickets"
            },
            {
                "emoji": "🎫",
                "title": "Ticket"
            },
            {
                "emoji": "🎖️",
                "title": "Military Medal"
            },
            {
                "emoji": "🏆",
                "title": "Trophy"
            },
            {
                "emoji": "🏅",
                "title": "Sports Medal"
            },
            {
                "emoji": "🥇",
                "title": "1st Place Medal"
            },
            {
                "emoji": "🥈",
                "title": "2nd Place Medal"
            },
            {
                "emoji": "🥉",
                "title": "3rd Place Medal"
            },
            {
                "emoji": "⚽",
                "title": "Soccer Ball"
            },
            {
                "emoji": "⚾",
                "title": "Baseball"
            },
            {
                "emoji": "🥎",
                "title": "Softball"
            },
            {
                "emoji": "🏀",
                "title": "Basketball"
            },
            {
                "emoji": "🏐",
                "title": "Volleyball"
            },
            {
                "emoji": "🏈",
                "title": "American Football"
            },
            {
                "emoji": "🏉",
                "title": "Rugby Football"
            },
            {
                "emoji": "🎾",
                "title": "Tennis"
            },
            {
                "emoji": "🥏",
                "title": "Flying Disc"
            },
            {
                "emoji": "🎳",
                "title": "Bowling"
            },
            {
                "emoji": "🏏",
                "title": "Cricket Game"
            },
            {
                "emoji": "🏑",
                "title": "Field Hockey"
            },
            {
                "emoji": "🏒",
                "title": "Ice Hockey"
            },
            {
                "emoji": "🥍",
                "title": "Lacrosse"
            },
            {
                "emoji": "🏓",
                "title": "Ping Pong"
            },
            {
                "emoji": "🏸",
                "title": "Badminton"
            },
            {
                "emoji": "🥊",
                "title": "Boxing Glove"
            },
            {
                "emoji": "🥋",
                "title": "Martial Arts Uniform"
            },
            {
                "emoji": "🥅",
                "title": "Goal Net"
            },
            {
                "emoji": "⛳",
                "title": "Flag in Hole"
            },
            {
                "emoji": "⛸️",
                "title": "Ice Skate"
            },
            {
                "emoji": "🎣",
                "title": "Fishing Pole"
            },
            {
                "emoji": "🎽",
                "title": "Running Shirt"
            },
            {
                "emoji": "🎿",
                "title": "Skis"
            },
            {
                "emoji": "🛷",
                "title": "Sled"
            },
            {
                "emoji": "🥌",
                "title": "Curling Stone"
            },
            {
                "emoji": "🎯",
                "title": "Bullseye"
            },
            {
                "emoji": "🎱",
                "title": "Pool 8 Ball"
            },
            {
                "emoji": "🎮",
                "title": "Video Game"
            },
            {
                "emoji": "🎰",
                "title": "Slot Machine"
            },
            {
                "emoji": "🎲",
                "title": "Game Die"
            },
            {
                "emoji": "🧩",
                "title": "Puzzle Piece"
            },
            {
                "emoji": "♟️",
                "title": "Chess Pawn"
            },
            {
                "emoji": "🎭",
                "title": "Performing Arts"
            },
            {
                "emoji": "🎨",
                "title": "Artist Palette"
            },
            {
                "emoji": "🧵",
                "title": "Thread"
            },
            {
                "emoji": "🧶",
                "title": "Yarn"
            },
            {
                "emoji": "🎼",
                "title": "Musical Score"
            },
            {
                "emoji": "🎤",
                "title": "Microphone"
            },
            {
                "emoji": "🎧",
                "title": "Headphone"
            },
            {
                "emoji": "🎷",
                "title": "Saxophone"
            },
            {
                "emoji": "🪗",
                "title": "Accordion"
            },
            {
                "emoji": "🎸",
                "title": "Guitar"
            },
            {
                "emoji": "🎹",
                "title": "Musical Keyboard"
            },
            {
                "emoji": "🎺",
                "title": "Trumpet"
            },
            {
                "emoji": "🎻",
                "title": "Violin"
            },
            {
                "emoji": "🥁",
                "title": "Drum"
            },
            {
                "emoji": "🪘",
                "title": "Long Drum"
            },
            {
                "emoji": "🎬",
                "title": "Clapper Board"
            },
            {
                "emoji": "🏹",
                "title": "Bow and Arrow"
            }
        ],
        'Travel-places': [
            {
                "emoji": "🚣",
                "title": "Person Rowing Boat"
            },
            {
                "emoji": "🗾",
                "title": "Map of Japan"
            },
            {
                "emoji": "🏔️",
                "title": "Snow-Capped Mountain"
            },
            {
                "emoji": "⛰️",
                "title": "Mountain"
            },
            {
                "emoji": "🌋",
                "title": "Volcano"
            },
            {
                "emoji": "🗻",
                "title": "Mount Fuji"
            },
            {
                "emoji": "🏕️",
                "title": "Camping"
            },
            {
                "emoji": "🏖️",
                "title": "Beach with Umbrella"
            },
            {
                "emoji": "🏜️",
                "title": "Desert"
            },
            {
                "emoji": "🏝️",
                "title": "Desert Island"
            },
            {
                "emoji": "🏞️",
                "title": "National Park"
            },
            {
                "emoji": "🏟️",
                "title": "Stadium"
            },
            {
                "emoji": "🏛️",
                "title": "Classical Building"
            },
            {
                "emoji": "🏗️",
                "title": "Building Construction"
            },
            {
                "emoji": "🛖",
                "title": "Hut"
            },
            {
                "emoji": "🏘️",
                "title": "Houses"
            },
            {
                "emoji": "🏚️",
                "title": "Derelict House"
            },
            {
                "emoji": "🏠",
                "title": "House"
            },
            {
                "emoji": "🏡",
                "title": "House with Garden"
            },
            {
                "emoji": "🏢",
                "title": "Office Building"
            },
            {
                "emoji": "🏣",
                "title": "Japanese Post Office"
            },
            {
                "emoji": "🏤",
                "title": "Post Office"
            },
            {
                "emoji": "🏥",
                "title": "Hospital"
            },
            {
                "emoji": "🏦",
                "title": "Bank"
            },
            {
                "emoji": "🏨",
                "title": "Hotel"
            },
            {
                "emoji": "🏩",
                "title": "Love Hotel"
            },
            {
                "emoji": "🏪",
                "title": "Convenience Store"
            },
            {
                "emoji": "🏫",
                "title": "School"
            },
            {
                "emoji": "🏬",
                "title": "Department Store"
            },
            {
                "emoji": "🏭",
                "title": "Factory"
            },
            {
                "emoji": "🏯",
                "title": "Japanese Castle"
            },
            {
                "emoji": "🏰",
                "title": "Castle"
            },
            {
                "emoji": "💒",
                "title": "Wedding"
            },
            {
                "emoji": "🗼",
                "title": "Tokyo Tower"
            },
            {
                "emoji": "🗽",
                "title": "Statue of Liberty"
            },
            {
                "emoji": "⛪",
                "title": "Church"
            },
            {
                "emoji": "🕌",
                "title": "Mosque"
            },
            {
                "emoji": "🛕",
                "title": "Hindu Temple"
            },
            {
                "emoji": "🕍",
                "title": "Synagogue"
            },
            {
                "emoji": "⛩️",
                "title": "Shinto Shrine"
            },
            {
                "emoji": "🕋",
                "title": "Kaaba"
            },
            {
                "emoji": "⛲",
                "title": "Fountain"
            },
            {
                "emoji": "⛺",
                "title": "Tent"
            },
            {
                "emoji": "🌁",
                "title": "Foggy"
            },
            {
                "emoji": "🌃",
                "title": "Night with Stars"
            },
            {
                "emoji": "🏙️",
                "title": "Cityscape"
            },
            {
                "emoji": "🌄",
                "title": "Sunrise Over Mountains"
            },
            {
                "emoji": "🌅",
                "title": "Sunrise"
            },
            {
                "emoji": "🌆",
                "title": "Cityscape at Dusk"
            },
            {
                "emoji": "🌇",
                "title": "Sunset"
            },
            {
                "emoji": "🌉",
                "title": "Bridge at Night"
            },
            {
                "emoji": "🎠",
                "title": "Carousel Horse"
            },
            {
                "emoji": "🎡",
                "title": "Ferris Wheel"
            },
            {
                "emoji": "🎢",
                "title": "Roller Coaster"
            },
            {
                "emoji": "🚂",
                "title": "Locomotive"
            },
            {
                "emoji": "🚃",
                "title": "Railway Car"
            },
            {
                "emoji": "🚄",
                "title": "High-Speed Train"
            },
            {
                "emoji": "🚅",
                "title": "Bullet Train"
            },
            {
                "emoji": "🚆",
                "title": "Train"
            },
            {
                "emoji": "🚇",
                "title": "Metro"
            },
            {
                "emoji": "🚈",
                "title": "Light Rail"
            },
            {
                "emoji": "🚉",
                "title": "Station"
            },
            {
                "emoji": "🚊",
                "title": "Tram"
            },
            {
                "emoji": "🚝",
                "title": "Monorail"
            },
            {
                "emoji": "🚞",
                "title": "Mountain Railway"
            },
            {
                "emoji": "🚋",
                "title": "Tram Car"
            },
            {
                "emoji": "🚌",
                "title": "Bus"
            },
            {
                "emoji": "🚍",
                "title": "Oncoming Bus"
            },
            {
                "emoji": "🚎",
                "title": "Trolleybus"
            },
            {
                "emoji": "🚐",
                "title": "Minibus"
            },
            {
                "emoji": "🚑",
                "title": "Ambulance"
            },
            {
                "emoji": "🚒",
                "title": "Fire Engine"
            },
            {
                "emoji": "🚓",
                "title": "Police Car"
            },
            {
                "emoji": "🚔",
                "title": "Oncoming Police Car"
            },
            {
                "emoji": "🚕",
                "title": "Taxi"
            },
            {
                "emoji": "🚖",
                "title": "Oncoming Taxi"
            },
            {
                "emoji": "🚗",
                "title": "Automobile"
            },
            {
                "emoji": "🚘",
                "title": "Oncoming Automobile"
            },
            {
                "emoji": "🚙",
                "title": "Sport Utility Vehicle"
            },
            {
                "emoji": "🛻",
                "title": "Pickup Truck"
            },
            {
                "emoji": "🚚",
                "title": "Delivery Truck"
            },
            {
                "emoji": "🚛",
                "title": "Articulated Lorry"
            },
            {
                "emoji": "🚜",
                "title": "Tractor"
            },
            {
                "emoji": "🏎️",
                "title": "Racing Car"
            },
            {
                "emoji": "🏍️",
                "title": "Motorcycle"
            },
            {
                "emoji": "🛵",
                "title": "Motor Scooter"
            },
            {
                "emoji": "🛺",
                "title": "Auto Rickshaw"
            },
            {
                "emoji": "🚲",
                "title": "Bicycle"
            },
            {
                "emoji": "🛴",
                "title": "Kick Scooter"
            },
            {
                "emoji": "🚏",
                "title": "Bus Stop"
            },
            {
                "emoji": "🛣️",
                "title": "Motorway"
            },
            {
                "emoji": "🛤️",
                "title": "Railway Track"
            },
            {
                "emoji": "⛽",
                "title": "Fuel Pump"
            },
            {
                "emoji": "🚨",
                "title": "Police Car Light"
            },
            {
                "emoji": "🚥",
                "title": "Horizontal Traffic Light"
            },
            {
                "emoji": "🚦",
                "title": "Vertical Traffic Light"
            },
            {
                "emoji": "🚧",
                "title": "Construction"
            },
            {
                "emoji": "⚓",
                "title": "Anchor"
            },
            {
                "emoji": "⛵",
                "title": "Sailboat"
            },
            {
                "emoji": "🚤",
                "title": "Speedboat"
            },
            {
                "emoji": "🛳️",
                "title": "Passenger Ship"
            },
            {
                "emoji": "⛴️",
                "title": "Ferry"
            },
            {
                "emoji": "🛥️",
                "title": "Motor Boat"
            },
            {
                "emoji": "🚢",
                "title": "Ship"
            },
            {
                "emoji": "✈️",
                "title": "Airplane"
            },
            {
                "emoji": "🛩️",
                "title": "Small Airplane"
            },
            {
                "emoji": "🛫",
                "title": "Airplane Departure"
            },
            {
                "emoji": "🛬",
                "title": "Airplane Arrival"
            },
            {
                "emoji": "🪂",
                "title": "Parachute"
            },
            {
                "emoji": "💺",
                "title": "Seat"
            },
            {
                "emoji": "🚁",
                "title": "Helicopter"
            },
            {
                "emoji": "🚟",
                "title": "Suspension Railway"
            },
            {
                "emoji": "🚠",
                "title": "Mountain Cableway"
            },
            {
                "emoji": "🚡",
                "title": "Aerial Tramway"
            },
            {
                "emoji": "🛰️",
                "title": "Satellite"
            },
            {
                "emoji": "🚀",
                "title": "Rocket"
            },
            {
                "emoji": "🛸",
                "title": "Flying Saucer"
            },
            {
                "emoji": "🪐",
                "title": "Ringed Planet"
            },
            {
                "emoji": "🌠",
                "title": "Shooting Star"
            },
            {
                "emoji": "🌌",
                "title": "Milky Way"
            },
            {
                "emoji": "⛱️",
                "title": "Umbrella on Ground"
            },
            {
                "emoji": "🎆",
                "title": "Fireworks"
            },
            {
                "emoji": "🎇",
                "title": "Sparkler"
            },
            {
                "emoji": "🎑",
                "title": "Moon Viewing Ceremony"
            },
            {
                "emoji": "💴",
                "title": "Yen Banknote"
            },
            {
                "emoji": "💵",
                "title": "Dollar Banknote"
            },
            {
                "emoji": "💶",
                "title": "Euro Banknote"
            },
            {
                "emoji": "💷",
                "title": "Pound Banknote"
            },
            {
                "emoji": "🗿",
                "title": "Moai"
            },
            {
                "emoji": "🛂",
                "title": "Passport Control"
            },
            {
                "emoji": "🛃",
                "title": "Customs"
            },
            {
                "emoji": "🛄",
                "title": "Baggage Claim"
            },
            {
                "emoji": "🛅",
                "title": "Left Luggage"
            }
        ],
        'Objects': [
            {
                "emoji": "💌",
                "title": "Love Letter"
            },
            {
                "emoji": "🕳️",
                "title": "Hole"
            },
            {
                "emoji": "💣",
                "title": "Bomb"
            },
            {
                "emoji": "🛀",
                "title": "Person Taking Bath"
            },
            {
                "emoji": "🛌",
                "title": "Person in Bed"
            },
            {
                "emoji": "🔪",
                "title": "Kitchen Knife"
            },
            {
                "emoji": "🏺",
                "title": "Amphora"
            },
            {
                "emoji": "🗺️",
                "title": "World Map"
            },
            {
                "emoji": "🧭",
                "title": "Compass"
            },
            {
                "emoji": "🧱",
                "title": "Brick"
            },
            {
                "emoji": "💈",
                "title": "Barber Pole"
            },
            {
                "emoji": "🦽",
                "title": "Manual Wheelchair"
            },
            {
                "emoji": "🦼",
                "title": "Motorized Wheelchair"
            },
            {
                "emoji": "🛢️",
                "title": "Oil Drum"
            },
            {
                "emoji": "🛎️",
                "title": "Bellhop Bell"
            },
            {
                "emoji": "🧳",
                "title": "Luggage"
            },
            {
                "emoji": "⌛",
                "title": "Hourglass Done"
            },
            {
                "emoji": "⏳",
                "title": "Hourglass Not Done"
            },
            {
                "emoji": "⌚",
                "title": "Watch"
            },
            {
                "emoji": "⏰",
                "title": "Alarm Clock"
            },
            {
                "emoji": "⏱️",
                "title": "Stopwatch"
            },
            {
                "emoji": "⏲️",
                "title": "Timer Clock"
            },
            {
                "emoji": "🕰️",
                "title": "Mantelpiece Clock"
            },
            {
                "emoji": "🌡️",
                "title": "Thermometer"
            },
            {
                "emoji": "⛱️",
                "title": "Umbrella on Ground"
            },
            {
                "emoji": "🧨",
                "title": "Firecracker"
            },
            {
                "emoji": "🎈",
                "title": "Balloon"
            },
            {
                "emoji": "🎉",
                "title": "Party Popper"
            },
            {
                "emoji": "🎊",
                "title": "Confetti Ball"
            },
            {
                "emoji": "🎎",
                "title": "Japanese Dolls"
            },
            {
                "emoji": "🎏",
                "title": "Carp Streamer"
            },
            {
                "emoji": "🎐",
                "title": "Wind Chime"
            },
            {
                "emoji": "🧧",
                "title": "Red Envelope"
            },
            {
                "emoji": "🎀",
                "title": "Ribbon"
            },
            {
                "emoji": "🎁",
                "title": "Wrapped Gift"
            },
            {
                "emoji": "🤿",
                "title": "Diving Mask"
            },
            {
                "emoji": "🪀",
                "title": "Yo-Yo"
            },
            {
                "emoji": "🪁",
                "title": "Kite"
            },
            {
                "emoji": "🔮",
                "title": "Crystal Ball"
            },
            {
                "emoji": "🪄",
                "title": "Magic Wand"
            },
            {
                "emoji": "🧿",
                "title": "Nazar Amulet"
            },
            {
                "emoji": "🕹️",
                "title": "Joystick"
            },
            {
                "emoji": "🧸",
                "title": "Teddy Bear"
            },
            {
                "emoji": "🪅",
                "title": "Piñata"
            },
            {
                "emoji": "🪆",
                "title": "Nesting Dolls"
            },
            {
                "emoji": "🖼️",
                "title": "Framed Picture"
            },
            {
                "emoji": "🧵",
                "title": "Thread"
            },
            {
                "emoji": "🪡",
                "title": "Sewing Needle"
            },
            {
                "emoji": "🧶",
                "title": "Yarn"
            },
            {
                "emoji": "🪢",
                "title": "Knot"
            },
            {
                "emoji": "🛍️",
                "title": "Shopping Bags"
            },
            {
                "emoji": "📿",
                "title": "Prayer Beads"
            },
            {
                "emoji": "💎",
                "title": "Gem Stone"
            },
            {
                "emoji": "📯",
                "title": "Postal Horn"
            },
            {
                "emoji": "🎙️",
                "title": "Studio Microphone"
            },
            {
                "emoji": "🎚️",
                "title": "Level Slider"
            },
            {
                "emoji": "🎛️",
                "title": "Control Knobs"
            },
            {
                "emoji": "📻",
                "title": "Radio"
            },
            {
                "emoji": "🪕",
                "title": "Banjo"
            },
            {
                "emoji": "📱",
                "title": "Mobile Phone"
            },
            {
                "emoji": "📲",
                "title": "Mobile Phone with Arrow"
            },
            {
                "emoji": "☎️",
                "title": "Telephone"
            },
            {
                "emoji": "📞",
                "title": "Telephone Receiver"
            },
            {
                "emoji": "📟",
                "title": "Pager"
            },
            {
                "emoji": "📠",
                "title": "Fax Machine"
            },
            {
                "emoji": "🔋",
                "title": "Battery"
            },
            {
                "emoji": "🔌",
                "title": "Electric Plug"
            },
            {
                "emoji": "💻",
                "title": "Laptop"
            },
            {
                "emoji": "🖥️",
                "title": "Desktop Computer"
            },
            {
                "emoji": "🖨️",
                "title": "Printer"
            },
            {
                "emoji": "⌨️",
                "title": "Keyboard"
            },
            {
                "emoji": "🖱️",
                "title": "Computer Mouse"
            },
            {
                "emoji": "🖲️",
                "title": "Trackball"
            },
            {
                "emoji": "💽",
                "title": "Computer Disk"
            },
            {
                "emoji": "💾",
                "title": "Floppy Disk"
            },
            {
                "emoji": "💿",
                "title": "Optical Disk"
            },
            {
                "emoji": "📀",
                "title": "DVD"
            },
            {
                "emoji": "🧮",
                "title": "Abacus"
            },
            {
                "emoji": "🎥",
                "title": "Movie Camera"
            },
            {
                "emoji": "🎞️",
                "title": "Film Frames"
            },
            {
                "emoji": "📽️",
                "title": "Film Projector"
            },
            {
                "emoji": "📺",
                "title": "Television"
            },
            {
                "emoji": "📷",
                "title": "Camera"
            },
            {
                "emoji": "📸",
                "title": "Camera with Flash"
            },
            {
                "emoji": "📹",
                "title": "Video Camera"
            },
            {
                "emoji": "📼",
                "title": "Videocassette"
            },
            {
                "emoji": "🔍",
                "title": "Magnifying Glass Tilted Left"
            },
            {
                "emoji": "🔎",
                "title": "Magnifying Glass Tilted Right"
            },
            {
                "emoji": "🕯️",
                "title": "Candle"
            },
            {
                "emoji": "💡",
                "title": "Light Bulb"
            },
            {
                "emoji": "🔦",
                "title": "Flashlight"
            },
            {
                "emoji": "🏮",
                "title": "Red Paper Lantern"
            },
            {
                "emoji": "🪔",
                "title": "Diya Lamp"
            },
            {
                "emoji": "📔",
                "title": "Notebook with Decorative Cover"
            },
            {
                "emoji": "📕",
                "title": "Closed Book"
            },
            {
                "emoji": "📖",
                "title": "Open Book"
            },
            {
                "emoji": "📗",
                "title": "Green Book"
            },
            {
                "emoji": "📘",
                "title": "Blue Book"
            },
            {
                "emoji": "📙",
                "title": "Orange Book"
            },
            {
                "emoji": "📚",
                "title": "Books"
            },
            {
                "emoji": "📓",
                "title": "Notebook"
            },
            {
                "emoji": "📒",
                "title": "Ledger"
            },
            {
                "emoji": "📃",
                "title": "Page with Curl"
            },
            {
                "emoji": "📜",
                "title": "Scroll"
            },
            {
                "emoji": "📄",
                "title": "Page Facing Up"
            },
            {
                "emoji": "📰",
                "title": "Newspaper"
            },
            {
                "emoji": "🗞️",
                "title": "Rolled-Up Newspaper"
            },
            {
                "emoji": "📑",
                "title": "Bookmark Tabs"
            },
            {
                "emoji": "🔖",
                "title": "Bookmark"
            },
            {
                "emoji": "🏷️",
                "title": "Label"
            },
            {
                "emoji": "💰",
                "title": "Money Bag"
            },
            {
                "emoji": "🪙",
                "title": "Coin"
            },
            {
                "emoji": "💴",
                "title": "Yen Banknote"
            },
            {
                "emoji": "💵",
                "title": "Dollar Banknote"
            },
            {
                "emoji": "💶",
                "title": "Euro Banknote"
            },
            {
                "emoji": "💷",
                "title": "Pound Banknote"
            },
            {
                "emoji": "💸",
                "title": "Money with Wings"
            },
            {
                "emoji": "💳",
                "title": "Credit Card"
            },
            {
                "emoji": "🧾",
                "title": "Receipt"
            },
            {
                "emoji": "✉️",
                "title": "Envelope"
            },
            {
                "emoji": "📧",
                "title": "E-Mail"
            },
            {
                "emoji": "📨",
                "title": "Incoming Envelope"
            },
            {
                "emoji": "📩",
                "title": "Envelope with Arrow"
            },
            {
                "emoji": "📤",
                "title": "Outbox Tray"
            },
            {
                "emoji": "📥",
                "title": "Inbox Tray"
            },
            {
                "emoji": "📦",
                "title": "Package"
            },
            {
                "emoji": "📫",
                "title": "Closed Mailbox with Raised Flag"
            },
            {
                "emoji": "📪",
                "title": "Closed Mailbox with Lowered Flag"
            },
            {
                "emoji": "📬",
                "title": "Open Mailbox with Raised Flag"
            },
            {
                "emoji": "📭",
                "title": "Open Mailbox with Lowered Flag"
            },
            {
                "emoji": "📮",
                "title": "Postbox"
            },
            {
                "emoji": "🗳️",
                "title": "Ballot Box with Ballot"
            },
            {
                "emoji": "✏️",
                "title": "Pencil"
            },
            {
                "emoji": "✒️",
                "title": "Black Nib"
            },
            {
                "emoji": "🖋️",
                "title": "Fountain Pen"
            },
            {
                "emoji": "🖊️",
                "title": "Pen"
            },
            {
                "emoji": "🖌️",
                "title": "Paintbrush"
            },
            {
                "emoji": "🖍️",
                "title": "Crayon"
            },
            {
                "emoji": "📝",
                "title": "Memo"
            },
            {
                "emoji": "📁",
                "title": "File Folder"
            },
            {
                "emoji": "📂",
                "title": "Open File Folder"
            },
            {
                "emoji": "🗂️",
                "title": "Card Index Dividers"
            },
            {
                "emoji": "📅",
                "title": "Calendar"
            },
            {
                "emoji": "📆",
                "title": "Tear-Off Calendar"
            },
            {
                "emoji": "🗒️",
                "title": "Spiral Notepad"
            },
            {
                "emoji": "🗓️",
                "title": "Spiral Calendar"
            },
            {
                "emoji": "📇",
                "title": "Card Index"
            },
            {
                "emoji": "📈",
                "title": "Chart Increasing"
            },
            {
                "emoji": "📉",
                "title": "Chart Decreasing"
            },
            {
                "emoji": "📊",
                "title": "Bar Chart"
            },
            {
                "emoji": "📋",
                "title": "Clipboard"
            },
            {
                "emoji": "📌",
                "title": "Pushpin"
            },
            {
                "emoji": "📍",
                "title": "Round Pushpin"
            },
            {
                "emoji": "📎",
                "title": "Paperclip"
            },
            {
                "emoji": "🖇️",
                "title": "Linked Paperclips"
            },
            {
                "emoji": "📏",
                "title": "Straight Ruler"
            },
            {
                "emoji": "📐",
                "title": "Triangular Ruler"
            },
            {
                "emoji": "✂️",
                "title": "Scissors"
            },
            {
                "emoji": "🗃️",
                "title": "Card File Box"
            },
            {
                "emoji": "🗄️",
                "title": "File Cabinet"
            },
            {
                "emoji": "🗑️",
                "title": "Wastebasket"
            },
            {
                "emoji": "🔒",
                "title": "Locked"
            },
            {
                "emoji": "🔓",
                "title": "Unlocked"
            },
            {
                "emoji": "🔏",
                "title": "Locked with Pen"
            },
            {
                "emoji": "🔐",
                "title": "Locked with Key"
            },
            {
                "emoji": "🔑",
                "title": "Key"
            },
            {
                "emoji": "🗝️",
                "title": "Old Key"
            },
            {
                "emoji": "🔨",
                "title": "Hammer"
            },
            {
                "emoji": "🪓",
                "title": "Axe"
            },
            {
                "emoji": "⛏️",
                "title": "Pick"
            },
            {
                "emoji": "⚒️",
                "title": "Hammer and Pick"
            },
            {
                "emoji": "🛠️",
                "title": "Hammer and Wrench"
            },
            {
                "emoji": "🗡️",
                "title": "Dagger"
            },
            {
                "emoji": "⚔️",
                "title": "Crossed Swords"
            },
            {
                "emoji": "🔫",
                "title": "Water Pistol"
            },
            {
                "emoji": "🪃",
                "title": "Boomerang"
            },
            {
                "emoji": "🛡️",
                "title": "Shield"
            },
            {
                "emoji": "🪚",
                "title": "Carpentry Saw"
            },
            {
                "emoji": "🔧",
                "title": "Wrench"
            },
            {
                "emoji": "🪛",
                "title": "Screwdriver"
            },
            {
                "emoji": "🔩",
                "title": "Nut and Bolt"
            },
            {
                "emoji": "⚙️",
                "title": "Gear"
            },
            {
                "emoji": "🗜️",
                "title": "Clamp"
            },
            {
                "emoji": "⚖️",
                "title": "Balance Scale"
            },
            {
                "emoji": "🦯",
                "title": "White Cane"
            },
            {
                "emoji": "🔗",
                "title": "Link"
            },
            {
                "emoji": "⛓️",
                "title": "Chains"
            },
            {
                "emoji": "🪝",
                "title": "Hook"
            },
            {
                "emoji": "🧰",
                "title": "Toolbox"
            },
            {
                "emoji": "🧲",
                "title": "Magnet"
            },
            {
                "emoji": "🪜",
                "title": "Ladder"
            },
            {
                "emoji": "⚗️",
                "title": "Alembic"
            },
            {
                "emoji": "🧪",
                "title": "Test Tube"
            },
            {
                "emoji": "🧫",
                "title": "Petri Dish"
            },
            {
                "emoji": "🧬",
                "title": "DNA"
            },
            {
                "emoji": "🔬",
                "title": "Microscope"
            },
            {
                "emoji": "🔭",
                "title": "Telescope"
            },
            {
                "emoji": "📡",
                "title": "Satellite Antenna"
            },
            {
                "emoji": "💉",
                "title": "Syringe"
            },
            {
                "emoji": "🩸",
                "title": "Drop of Blood"
            },
            {
                "emoji": "💊",
                "title": "Pill"
            },
            {
                "emoji": "🩹",
                "title": "Adhesive Bandage"
            },
            {
                "emoji": "🩺",
                "title": "Stethoscope"
            },
            {
                "emoji": "🚪",
                "title": "Door"
            },
            {
                "emoji": "🪞",
                "title": "Mirror"
            },
            {
                "emoji": "🪟",
                "title": "Window"
            },
            {
                "emoji": "🛏️",
                "title": "Bed"
            },
            {
                "emoji": "🛋️",
                "title": "Couch and Lamp"
            },
            {
                "emoji": "🪑",
                "title": "Chair"
            },
            {
                "emoji": "🚽",
                "title": "Toilet"
            },
            {
                "emoji": "🪠",
                "title": "Plunger"
            },
            {
                "emoji": "🚿",
                "title": "Shower"
            },
            {
                "emoji": "🛁",
                "title": "Bathtub"
            },
            {
                "emoji": "🪤",
                "title": "Mouse Trap"
            },
            {
                "emoji": "🪒",
                "title": "Razor"
            },
            {
                "emoji": "🧴",
                "title": "Lotion Bottle"
            },
            {
                "emoji": "🧷",
                "title": "Safety Pin"
            },
            {
                "emoji": "🧹",
                "title": "Broom"
            },
            {
                "emoji": "🧺",
                "title": "Basket"
            },
            {
                "emoji": "🧻",
                "title": "Roll of Paper"
            },
            {
                "emoji": "🪣",
                "title": "Bucket"
            },
            {
                "emoji": "🧼",
                "title": "Soap"
            },
            {
                "emoji": "🪥",
                "title": "Toothbrush"
            },
            {
                "emoji": "🧽",
                "title": "Sponge"
            },
            {
                "emoji": "🧯",
                "title": "Fire Extinguisher"
            },
            {
                "emoji": "🛒",
                "title": "Shopping Cart"
            },
            {
                "emoji": "🚬",
                "title": "Cigarette"
            },
            {
                "emoji": "⚰️",
                "title": "Coffin"
            },
            {
                "emoji": "🪦",
                "title": "Headstone"
            },
            {
                "emoji": "⚱️",
                "title": "Funeral Urn"
            },
            {
                "emoji": "🗿",
                "title": "Moai"
            },
            {
                "emoji": "🪧",
                "title": "Placard"
            },
            {
                "emoji": "🚰",
                "title": "Potable Water"
            }
        ],
        'Symbols': [
            {
                "emoji": "💘",
                "title": "Heart with Arrow"
            },
            {
                "emoji": "💝",
                "title": "Heart with Ribbon"
            },
            {
                "emoji": "💖",
                "title": "Sparkling Heart"
            },
            {
                "emoji": "💗",
                "title": "Growing Heart"
            },
            {
                "emoji": "💓",
                "title": "Beating Heart"
            },
            {
                "emoji": "💞",
                "title": "Revolving Hearts"
            },
            {
                "emoji": "💕",
                "title": "Two Hearts"
            },
            {
                "emoji": "💟",
                "title": "Heart Decoration"
            },
            {
                "emoji": "❣️",
                "title": "Heart Exclamation"
            },
            {
                "emoji": "💔",
                "title": "Broken Heart"
            },
            {
                "emoji": "❤️‍🔥",
                "title": "Heart on Fire"
            },
            {
                "emoji": "❤️‍🩹",
                "title": "Mending Heart"
            },
            {
                "emoji": "❤️",
                "title": "Red Heart"
            },
            {
                "emoji": "🧡",
                "title": "Orange Heart"
            },
            {
                "emoji": "💛",
                "title": "Yellow Heart"
            },
            {
                "emoji": "💚",
                "title": "Green Heart"
            },
            {
                "emoji": "💙",
                "title": "Blue Heart"
            },
            {
                "emoji": "💜",
                "title": "Purple Heart"
            },
            {
                "emoji": "🤎",
                "title": "Brown Heart"
            },
            {
                "emoji": "🖤",
                "title": "Black Heart"
            },
            {
                "emoji": "🤍",
                "title": "White Heart"
            },
            {
                "emoji": "💯",
                "title": "Hundred Points"
            },
            {
                "emoji": "💢",
                "title": "Anger Symbol"
            },
            {
                "emoji": "💬",
                "title": "Speech Balloon"
            },
            {
                "emoji": "👁️‍🗨️",
                "title": "Eye in Speech Bubble"
            },
            {
                "emoji": "🗨️",
                "title": "Left Speech Bubble"
            },
            {
                "emoji": "🗯️",
                "title": "Right Anger Bubble"
            },
            {
                "emoji": "💭",
                "title": "Thought Balloon"
            },
            {
                "emoji": "💤",
                "title": "Zzz"
            },
            {
                "emoji": "💮",
                "title": "White Flower"
            },
            {
                "emoji": "♨️",
                "title": "Hot Springs"
            },
            {
                "emoji": "💈",
                "title": "Barber Pole"
            },
            {
                "emoji": "🛑",
                "title": "Stop Sign"
            },
            {
                "emoji": "🕛",
                "title": "Twelve O’Clock"
            },
            {
                "emoji": "🕧",
                "title": "Twelve-Thirty"
            },
            {
                "emoji": "🕐",
                "title": "One O’Clock"
            },
            {
                "emoji": "🕜",
                "title": "One-Thirty"
            },
            {
                "emoji": "🕑",
                "title": "Two O’Clock"
            },
            {
                "emoji": "🕝",
                "title": "Two-Thirty"
            },
            {
                "emoji": "🕒",
                "title": "Three O’Clock"
            },
            {
                "emoji": "🕞",
                "title": "Three-Thirty"
            },
            {
                "emoji": "🕓",
                "title": "Four O’Clock"
            },
            {
                "emoji": "🕟",
                "title": "Four-Thirty"
            },
            {
                "emoji": "🕔",
                "title": "Five O’Clock"
            },
            {
                "emoji": "🕠",
                "title": "Five-Thirty"
            },
            {
                "emoji": "🕕",
                "title": "Six O’Clock"
            },
            {
                "emoji": "🕡",
                "title": "Six-Thirty"
            },
            {
                "emoji": "🕖",
                "title": "Seven O’Clock"
            },
            {
                "emoji": "🕢",
                "title": "Seven-Thirty"
            },
            {
                "emoji": "🕗",
                "title": "Eight O’Clock"
            },
            {
                "emoji": "🕣",
                "title": "Eight-Thirty"
            },
            {
                "emoji": "🕘",
                "title": "Nine O’Clock"
            },
            {
                "emoji": "🕤",
                "title": "Nine-Thirty"
            },
            {
                "emoji": "🕙",
                "title": "Ten O’Clock"
            },
            {
                "emoji": "🕥",
                "title": "Ten-Thirty"
            },
            {
                "emoji": "🕚",
                "title": "Eleven O’Clock"
            },
            {
                "emoji": "🕦",
                "title": "Eleven-Thirty"
            },
            {
                "emoji": "🌀",
                "title": "Cyclone"
            },
            {
                "emoji": "♠️",
                "title": "Spade Suit"
            },
            {
                "emoji": "♥️",
                "title": "Heart Suit"
            },
            {
                "emoji": "♦️",
                "title": "Diamond Suit"
            },
            {
                "emoji": "♣️",
                "title": "Club Suit"
            },
            {
                "emoji": "🃏",
                "title": "Joker"
            },
            {
                "emoji": "🀄",
                "title": "Mahjong Red Dragon"
            },
            {
                "emoji": "🎴",
                "title": "Flower Playing Cards"
            },
            {
                "emoji": "🔇",
                "title": "Muted Speaker"
            },
            {
                "emoji": "🔈",
                "title": "Speaker Low Volume"
            },
            {
                "emoji": "🔉",
                "title": "Speaker Medium Volume"
            },
            {
                "emoji": "🔊",
                "title": "Speaker High Volume"
            },
            {
                "emoji": "📢",
                "title": "Loudspeaker"
            },
            {
                "emoji": "📣",
                "title": "Megaphone"
            },
            {
                "emoji": "📯",
                "title": "Postal Horn"
            },
            {
                "emoji": "🔔",
                "title": "Bell"
            },
            {
                "emoji": "🔕",
                "title": "Bell with Slash"
            },
            {
                "emoji": "🎵",
                "title": "Musical Note"
            },
            {
                "emoji": "🎶",
                "title": "Musical Notes"
            },
            {
                "emoji": "💹",
                "title": "Chart Increasing with Yen"
            },
            {
                "emoji": "🛗",
                "title": "Elevator"
            },
            {
                "emoji": "🏧",
                "title": "ATM Sign"
            },
            {
                "emoji": "🚮",
                "title": "Litter in Bin Sign"
            },
            {
                "emoji": "🚰",
                "title": "Potable Water"
            },
            {
                "emoji": "♿",
                "title": "Wheelchair Symbol"
            },
            {
                "emoji": "🚹",
                "title": "Men’s Room"
            },
            {
                "emoji": "🚺",
                "title": "Women’s Room"
            },
            {
                "emoji": "🚻",
                "title": "Restroom"
            },
            {
                "emoji": "🚼",
                "title": "Baby Symbol"
            },
            {
                "emoji": "🚾",
                "title": "Water Closet"
            },
            {
                "emoji": "⚠️",
                "title": "Warning"
            },
            {
                "emoji": "🚸",
                "title": "Children Crossing"
            },
            {
                "emoji": "⛔",
                "title": "No Entry"
            },
            {
                "emoji": "🚫",
                "title": "Prohibited"
            },
            {
                "emoji": "🚳",
                "title": "No Bicycles"
            },
            {
                "emoji": "🚭",
                "title": "No Smoking"
            },
            {
                "emoji": "🚯",
                "title": "No Littering"
            },
            {
                "emoji": "🚱",
                "title": "Non-Potable Water"
            },
            {
                "emoji": "🚷",
                "title": "No Pedestrians"
            },
            {
                "emoji": "📵",
                "title": "No Mobile Phones"
            },
            {
                "emoji": "🔞",
                "title": "No One Under Eighteen"
            },
            {
                "emoji": "☢️",
                "title": "Radioactive"
            },
            {
                "emoji": "☣️",
                "title": "Biohazard"
            },
            {
                "emoji": "⬆️",
                "title": "Up Arrow"
            },
            {
                "emoji": "↗️",
                "title": "Up-Right Arrow"
            },
            {
                "emoji": "➡️",
                "title": "Right Arrow"
            },
            {
                "emoji": "↘️",
                "title": "Down-Right Arrow"
            },
            {
                "emoji": "⬇️",
                "title": "Down Arrow"
            },
            {
                "emoji": "↙️",
                "title": "Down-Left Arrow"
            },
            {
                "emoji": "⬅️",
                "title": "Left Arrow"
            },
            {
                "emoji": "↖️",
                "title": "Up-Left Arrow"
            },
            {
                "emoji": "↕️",
                "title": "Up-Down Arrow"
            },
            {
                "emoji": "↔️",
                "title": "Left-Right Arrow"
            },
            {
                "emoji": "↩️",
                "title": "Right Arrow Curving Left"
            },
            {
                "emoji": "↪️",
                "title": "Left Arrow Curving Right"
            },
            {
                "emoji": "⤴️",
                "title": "Right Arrow Curving Up"
            },
            {
                "emoji": "⤵️",
                "title": "Right Arrow Curving Down"
            },
            {
                "emoji": "🔃",
                "title": "Clockwise Vertical Arrows"
            },
            {
                "emoji": "🔄",
                "title": "Counterclockwise Arrows Button"
            },
            {
                "emoji": "🔙",
                "title": "Back Arrow"
            },
            {
                "emoji": "🔚",
                "title": "End Arrow"
            },
            {
                "emoji": "🔛",
                "title": "On! Arrow"
            },
            {
                "emoji": "🔜",
                "title": "Soon Arrow"
            },
            {
                "emoji": "🔝",
                "title": "Top Arrow"
            },
            {
                "emoji": "🛐",
                "title": "Place of Worship"
            },
            {
                "emoji": "⚛️",
                "title": "Atom Symbol"
            },
            {
                "emoji": "🕉️",
                "title": "Om"
            },
            {
                "emoji": "✡️",
                "title": "Star of David"
            },
            {
                "emoji": "☸️",
                "title": "Wheel of Dharma"
            },
            {
                "emoji": "☯️",
                "title": "Yin Yang"
            },
            {
                "emoji": "✝️",
                "title": "Latin Cross"
            },
            {
                "emoji": "☦️",
                "title": "Orthodox Cross"
            },
            {
                "emoji": "☪️",
                "title": "Star and Crescent"
            },
            {
                "emoji": "☮️",
                "title": "Peace Symbol"
            },
            {
                "emoji": "🕎",
                "title": "Menorah"
            },
            {
                "emoji": "🔯",
                "title": "Dotted Six-Pointed Star"
            },
            {
                "emoji": "♈",
                "title": "Aries"
            },
            {
                "emoji": "♉",
                "title": "Taurus"
            },
            {
                "emoji": "♊",
                "title": "Gemini"
            },
            {
                "emoji": "♋",
                "title": "Cancer"
            },
            {
                "emoji": "♌",
                "title": "Leo"
            },
            {
                "emoji": "♍",
                "title": "Virgo"
            },
            {
                "emoji": "♎",
                "title": "Libra"
            },
            {
                "emoji": "♏",
                "title": "Scorpio"
            },
            {
                "emoji": "♐",
                "title": "Sagittarius"
            },
            {
                "emoji": "♑",
                "title": "Capricorn"
            },
            {
                "emoji": "♒",
                "title": "Aquarius"
            },
            {
                "emoji": "♓",
                "title": "Pisces"
            },
            {
                "emoji": "⛎",
                "title": "Ophiuchus"
            },
            {
                "emoji": "🔀",
                "title": "Shuffle Tracks Button"
            },
            {
                "emoji": "🔁",
                "title": "Repeat Button"
            },
            {
                "emoji": "🔂",
                "title": "Repeat Single Button"
            },
            {
                "emoji": "▶️",
                "title": "Play Button"
            },
            {
                "emoji": "⏩",
                "title": "Fast-Forward Button"
            },
            {
                "emoji": "⏭️",
                "title": "Next Track Button"
            },
            {
                "emoji": "⏯️",
                "title": "Play or Pause Button"
            },
            {
                "emoji": "◀️",
                "title": "Reverse Button"
            },
            {
                "emoji": "⏪",
                "title": "Fast Reverse Button"
            },
            {
                "emoji": "⏮️",
                "title": "Last Track Button"
            },
            {
                "emoji": "🔼",
                "title": "Upwards Button"
            },
            {
                "emoji": "⏫",
                "title": "Fast Up Button"
            },
            {
                "emoji": "🔽",
                "title": "Downwards Button"
            },
            {
                "emoji": "⏬",
                "title": "Fast Down Button"
            },
            {
                "emoji": "⏸️",
                "title": "Pause Button"
            },
            {
                "emoji": "⏹️",
                "title": "Stop Button"
            },
            {
                "emoji": "⏺️",
                "title": "Record Button"
            },
            {
                "emoji": "⏏️",
                "title": "Eject Button"
            },
            {
                "emoji": "🎦",
                "title": "Cinema"
            },
            {
                "emoji": "🔅",
                "title": "Dim Button"
            },
            {
                "emoji": "🔆",
                "title": "Bright Button"
            },
            {
                "emoji": "📶",
                "title": "Antenna Bars"
            },
            {
                "emoji": "📳",
                "title": "Vibration Mode"
            },
            {
                "emoji": "📴",
                "title": "Mobile Phone Off"
            },
            {
                "emoji": "♀️",
                "title": "Female Sign"
            },
            {
                "emoji": "♂️",
                "title": "Male Sign"
            },
            {
                "emoji": "✖️",
                "title": "Multiply"
            },
            {
                "emoji": "➕",
                "title": "Plus"
            },
            {
                "emoji": "➖",
                "title": "Minus"
            },
            {
                "emoji": "➗",
                "title": "Divide"
            },
            {
                "emoji": "♾️",
                "title": "Infinity"
            },
            {
                "emoji": "‼️",
                "title": "‼ Double Exclamation Mark"
            },
            {
                "emoji": "⁉️",
                "title": "⁉ Exclamation Question Mark"
            },
            {
                "emoji": "❓",
                "title": "Red Question Mark"
            },
            {
                "emoji": "❔",
                "title": "White Question Mark"
            },
            {
                "emoji": "❕",
                "title": "White Exclamation Mark"
            },
            {
                "emoji": "❗",
                "title": "Red Exclamation Mark"
            },
            {
                "emoji": "〰️",
                "title": "〰 Wavy Dash"
            },
            {
                "emoji": "💱",
                "title": "Currency Exchange"
            },
            {
                "emoji": "💲",
                "title": "Heavy Dollar Sign"
            },
            {
                "emoji": "⚕️",
                "title": "Medical Symbol"
            },
            {
                "emoji": "♻️",
                "title": "Recycling Symbol"
            },
            {
                "emoji": "⚜️",
                "title": "Fleur-de-lis"
            },
            {
                "emoji": "🔱",
                "title": "Trident Emblem"
            },
            {
                "emoji": "📛",
                "title": "Name Badge"
            },
            {
                "emoji": "🔰",
                "title": "Japanese Symbol for Beginner"
            },
            {
                "emoji": "⭕",
                "title": "Hollow Red Circle"
            },
            {
                "emoji": "✅",
                "title": "Check Mark Button"
            },
            {
                "emoji": "☑️",
                "title": "Check Box with Check"
            },
            {
                "emoji": "✔️",
                "title": "Check Mark"
            },
            {
                "emoji": "❌",
                "title": "Cross Mark"
            },
            {
                "emoji": "❎",
                "title": "Cross Mark Button"
            },
            {
                "emoji": "➰",
                "title": "Curly Loop"
            },
            {
                "emoji": "➿",
                "title": "Double Curly Loop"
            },
            {
                "emoji": "〽️",
                "title": "〽 Part Alternation Mark"
            },
            {
                "emoji": "✳️",
                "title": "Eight-Spoked Asterisk"
            },
            {
                "emoji": "✴️",
                "title": "Eight-Pointed Star"
            },
            {
                "emoji": "❇️",
                "title": "Sparkle"
            },
            {
                "emoji": "©️",
                "title": "Copyright"
            },
            {
                "emoji": "®️",
                "title": "Registered"
            },
            {
                "emoji": "™️",
                "title": "Trade Mark"
            },
            {
                "emoji": "#️⃣",
                "title": "# Keycap Number Sign"
            },
            {
                "emoji": "*️⃣",
                "title": "* Keycap Asterisk"
            },
            {
                "emoji": "0️⃣",
                "title": "0 Keycap Digit Zero"
            },
            {
                "emoji": "1️⃣",
                "title": "1 Keycap Digit One"
            },
            {
                "emoji": "2️⃣",
                "title": "2 Keycap Digit Two"
            },
            {
                "emoji": "3️⃣",
                "title": "3 Keycap Digit Three"
            },
            {
                "emoji": "4️⃣",
                "title": "4 Keycap Digit Four"
            },
            {
                "emoji": "5️⃣",
                "title": "5 Keycap Digit Five"
            },
            {
                "emoji": "6️⃣",
                "title": "6 Keycap Digit Six"
            },
            {
                "emoji": "7️⃣",
                "title": "7 Keycap Digit Seven"
            },
            {
                "emoji": "8️⃣",
                "title": "8 Keycap Digit Eight"
            },
            {
                "emoji": "9️⃣",
                "title": "9 Keycap Digit Nine"
            },
            {
                "emoji": "🔟",
                "title": "Keycap: 10"
            },
            {
                "emoji": "🔠",
                "title": "Input Latin Uppercase"
            },
            {
                "emoji": "🔡",
                "title": "Input Latin Lowercase"
            },
            {
                "emoji": "🔢",
                "title": "Input Numbers"
            },
            {
                "emoji": "🔣",
                "title": "Input Symbols"
            },
            {
                "emoji": "🔤",
                "title": "Input Latin Letters"
            },
            {
                "emoji": "🅰️",
                "title": "A Button (Blood Type)"
            },
            {
                "emoji": "🆎",
                "title": "AB Button (Blood Type)"
            },
            {
                "emoji": "🅱️",
                "title": "B Button (Blood Type)"
            },
            {
                "emoji": "🆑",
                "title": "CL Button"
            },
            {
                "emoji": "🆒",
                "title": "Cool Button"
            },
            {
                "emoji": "🆓",
                "title": "Free Button"
            },
            {
                "emoji": "ℹ️",
                "title": "ℹ Information"
            },
            {
                "emoji": "🆔",
                "title": "ID Button"
            },
            {
                "emoji": "Ⓜ️",
                "title": "Circled M"
            },
            {
                "emoji": "🆕",
                "title": "New Button"
            },
            {
                "emoji": "🆖",
                "title": "NG Button"
            },
            {
                "emoji": "🅾️",
                "title": "O Button (Blood Type)"
            },
            {
                "emoji": "🆗",
                "title": "OK Button"
            },
            {
                "emoji": "🅿️",
                "title": "P Button"
            },
            {
                "emoji": "🆘",
                "title": "SOS Button"
            },
            {
                "emoji": "🆙",
                "title": "Up! Button"
            },
            {
                "emoji": "🆚",
                "title": "Vs Button"
            },
            {
                "emoji": "🈁",
                "title": "Japanese “Here” Button"
            },
            {
                "emoji": "🈂️",
                "title": "Japanese “Service Charge” Button"
            },
            {
                "emoji": "🈷️",
                "title": "Japanese “Monthly Amount” Button"
            },
            {
                "emoji": "🈶",
                "title": "Japanese “Not Free of Charge” Button"
            },
            {
                "emoji": "🈯",
                "title": "Japanese “Reserved” Button"
            },
            {
                "emoji": "🉐",
                "title": "Japanese “Bargain” Button"
            },
            {
                "emoji": "🈹",
                "title": "Japanese “Discount” Button"
            },
            {
                "emoji": "🈚",
                "title": "Japanese “Free of Charge” Button"
            },
            {
                "emoji": "🈲",
                "title": "Japanese “Prohibited” Button"
            },
            {
                "emoji": "🉑",
                "title": "Japanese “Acceptable” Button"
            },
            {
                "emoji": "🈸",
                "title": "Japanese “Application” Button"
            },
            {
                "emoji": "🈴",
                "title": "Japanese “Passing Grade” Button"
            },
            {
                "emoji": "🈳",
                "title": "Japanese “Vacancy” Button"
            },
            {
                "emoji": "㊗️",
                "title": "Japanese “Congratulations” Button"
            },
            {
                "emoji": "㊙️",
                "title": "Japanese “Secret” Button"
            },
            {
                "emoji": "🈺",
                "title": "Japanese “Open for Business” Button"
            },
            {
                "emoji": "🈵",
                "title": "Japanese “No Vacancy” Button"
            },
            {
                "emoji": "🔴",
                "title": "Red Circle"
            },
            {
                "emoji": "🟠",
                "title": "Orange Circle"
            },
            {
                "emoji": "🟡",
                "title": "Yellow Circle"
            },
            {
                "emoji": "🟢",
                "title": "Green Circle"
            },
            {
                "emoji": "🔵",
                "title": "Blue Circle"
            },
            {
                "emoji": "🟣",
                "title": "Purple Circle"
            },
            {
                "emoji": "🟤",
                "title": "Brown Circle"
            },
            {
                "emoji": "⚫",
                "title": "Black Circle"
            },
            {
                "emoji": "⚪",
                "title": "White Circle"
            },
            {
                "emoji": "🟥",
                "title": "Red Square"
            },
            {
                "emoji": "🟧",
                "title": "Orange Square"
            },
            {
                "emoji": "🟨",
                "title": "Yellow Square"
            },
            {
                "emoji": "🟩",
                "title": "Green Square"
            },
            {
                "emoji": "🟦",
                "title": "Blue Square"
            },
            {
                "emoji": "🟪",
                "title": "Purple Square"
            },
            {
                "emoji": "🟫",
                "title": "Brown Square"
            },
            {
                "emoji": "⬛",
                "title": "Black Large Square"
            },
            {
                "emoji": "⬜",
                "title": "White Large Square"
            },
            {
                "emoji": "◼️",
                "title": "Black Medium Square"
            },
            {
                "emoji": "◻️",
                "title": "White Medium Square"
            },
            {
                "emoji": "◾",
                "title": "Black Medium-Small Square"
            },
            {
                "emoji": "◽",
                "title": "White Medium-Small Square"
            },
            {
                "emoji": "▪️",
                "title": "Black Small Square"
            },
            {
                "emoji": "▫️",
                "title": "White Small Square"
            },
            {
                "emoji": "🔶",
                "title": "Large Orange Diamond"
            },
            {
                "emoji": "🔷",
                "title": "Large Blue Diamond"
            },
            {
                "emoji": "🔸",
                "title": "Small Orange Diamond"
            },
            {
                "emoji": "🔹",
                "title": "Small Blue Diamond"
            },
            {
                "emoji": "🔺",
                "title": "Red Triangle Pointed Up"
            },
            {
                "emoji": "🔻",
                "title": "Red Triangle Pointed Down"
            },
            {
                "emoji": "💠",
                "title": "Diamond with a Dot"
            },
            {
                "emoji": "🔘",
                "title": "Radio Button"
            },
            {
                "emoji": "🔳",
                "title": "White Square Button"
            },
            {
                "emoji": "🔲",
                "title": "Black Square Button"
            }
        ],
        'Flags': [
            {
                "emoji": "🏁",
                "title": "Chequered Flag"
            },
            {
                "emoji": "🚩",
                "title": "Triangular Flag"
            },
            {
                "emoji": "🎌",
                "title": "Crossed Flags"
            },
            {
                "emoji": "🏴",
                "title": "Black Flag"
            },
            {
                "emoji": "🏳️",
                "title": "White Flag"
            },
            {
                "emoji": "🏳️‍🌈",
                "title": "Rainbow Flag"
            },
            {
                "emoji": "🏳️‍⚧️",
                "title": "Transgender Flag"
            },
            {
                "emoji": "🏴‍☠️",
                "title": "Pirate Flag"
            },
            {
                "emoji": "🇦🇨",
                "title": "Flag: Ascension Island"
            },
            {
                "emoji": "🇦🇩",
                "title": "Flag: Andorra"
            },
            {
                "emoji": "🇦🇪",
                "title": "Flag: United Arab Emirates"
            },
            {
                "emoji": "🇦🇫",
                "title": "Flag: Afghanistan"
            },
            {
                "emoji": "🇦🇬",
                "title": "Flag: Antigua & Barbuda"
            },
            {
                "emoji": "🇦🇮",
                "title": "Flag: Anguilla"
            },
            {
                "emoji": "🇦🇱",
                "title": "Flag: Albania"
            },
            {
                "emoji": "🇦🇲",
                "title": "Flag: Armenia"
            },
            {
                "emoji": "🇦🇴",
                "title": "Flag: Angola"
            },
            {
                "emoji": "🇦🇶",
                "title": "Flag: Antarctica"
            },
            {
                "emoji": "🇦🇷",
                "title": "Flag: Argentina"
            },
            {
                "emoji": "🇦🇸",
                "title": "Flag: American Samoa"
            },
            {
                "emoji": "🇦🇹",
                "title": "Flag: Austria"
            },
            {
                "emoji": "🇦🇺",
                "title": "Flag: Australia"
            },
            {
                "emoji": "🇦🇼",
                "title": "Flag: Aruba"
            },
            {
                "emoji": "🇦🇽",
                "title": "Flag: Åland Islands"
            },
            {
                "emoji": "🇦🇿",
                "title": "Flag: Azerbaijan"
            },
            {
                "emoji": "🇧🇦",
                "title": "Flag: Bosnia & Herzegovina"
            },
            {
                "emoji": "🇧🇧",
                "title": "Flag: Barbados"
            },
            {
                "emoji": "🇧🇩",
                "title": "Flag: Bangladesh"
            },
            {
                "emoji": "🇧🇪",
                "title": "Flag: Belgium"
            },
            {
                "emoji": "🇧🇫",
                "title": "Flag: Burkina Faso"
            },
            {
                "emoji": "🇧🇬",
                "title": "Flag: Bulgaria"
            },
            {
                "emoji": "🇧🇭",
                "title": "Flag: Bahrain"
            },
            {
                "emoji": "🇧🇮",
                "title": "Flag: Burundi"
            },
            {
                "emoji": "🇧🇯",
                "title": "Flag: Benin"
            },
            {
                "emoji": "🇧🇱",
                "title": "Flag: St. Barthélemy"
            },
            {
                "emoji": "🇧🇲",
                "title": "Flag: Bermuda"
            },
            {
                "emoji": "🇧🇳",
                "title": "Flag: Brunei"
            },
            {
                "emoji": "🇧🇴",
                "title": "Flag: Bolivia"
            },
            {
                "emoji": "🇧🇶",
                "title": "Flag: Caribbean Netherlands"
            },
            {
                "emoji": "🇧🇷",
                "title": "Flag: Brazil"
            },
            {
                "emoji": "🇧🇸",
                "title": "Flag: Bahamas"
            },
            {
                "emoji": "🇧🇹",
                "title": "Flag: Bhutan"
            },
            {
                "emoji": "🇧🇻",
                "title": "Flag: Bouvet Island"
            },
            {
                "emoji": "🇧🇼",
                "title": "Flag: Botswana"
            },
            {
                "emoji": "🇧🇾",
                "title": "Flag: Belarus"
            },
            {
                "emoji": "🇧🇿",
                "title": "Flag: Belize"
            },
            {
                "emoji": "🇨🇦",
                "title": "Flag: Canada"
            },
            {
                "emoji": "🇨🇨",
                "title": "Flag: Cocos (Keeling) Islands"
            },
            {
                "emoji": "🇨🇩",
                "title": "Flag: Congo - Kinshasa"
            },
            {
                "emoji": "🇨🇫",
                "title": "Flag: Central African Republic"
            },
            {
                "emoji": "🇨🇬",
                "title": "Flag: Congo - Brazzaville"
            },
            {
                "emoji": "🇨🇭",
                "title": "Flag: Switzerland"
            },
            {
                "emoji": "🇨🇮",
                "title": "Flag: Côte d’Ivoire"
            },
            {
                "emoji": "🇨🇰",
                "title": "Flag: Cook Islands"
            },
            {
                "emoji": "🇨🇱",
                "title": "Flag: Chile"
            },
            {
                "emoji": "🇨🇲",
                "title": "Flag: Cameroon"
            },
            {
                "emoji": "🇨🇳",
                "title": "Flag: China"
            },
            {
                "emoji": "🇨🇴",
                "title": "Flag: Colombia"
            },
            {
                "emoji": "🇨🇵",
                "title": "Flag: Clipperton Island"
            },
            {
                "emoji": "🇨🇷",
                "title": "Flag: Costa Rica"
            },
            {
                "emoji": "🇨🇺",
                "title": "Flag: Cuba"
            },
            {
                "emoji": "🇨🇻",
                "title": "Flag: Cape Verde"
            },
            {
                "emoji": "🇨🇼",
                "title": "Flag: Curaçao"
            },
            {
                "emoji": "🇨🇽",
                "title": "Flag: Christmas Island"
            },
            {
                "emoji": "🇨🇾",
                "title": "Flag: Cyprus"
            },
            {
                "emoji": "🇨🇿",
                "title": "Flag: Czechia"
            },
            {
                "emoji": "🇩🇪",
                "title": "Flag: Germany"
            },
            {
                "emoji": "🇩🇬",
                "title": "Flag: Diego Garcia"
            },
            {
                "emoji": "🇩🇯",
                "title": "Flag: Djibouti"
            },
            {
                "emoji": "🇩🇰",
                "title": "Flag: Denmark"
            },
            {
                "emoji": "🇩🇲",
                "title": "Flag: Dominica"
            },
            {
                "emoji": "🇩🇴",
                "title": "Flag: Dominican Republic"
            },
            {
                "emoji": "🇩🇿",
                "title": "Flag: Algeria"
            },
            {
                "emoji": "🇪🇦",
                "title": "Flag: Ceuta & Melilla"
            },
            {
                "emoji": "🇪🇨",
                "title": "Flag: Ecuador"
            },
            {
                "emoji": "🇪🇪",
                "title": "Flag: Estonia"
            },
            {
                "emoji": "🇪🇬",
                "title": "Flag: Egypt"
            },
            {
                "emoji": "🇪🇭",
                "title": "Flag: Western Sahara"
            },
            {
                "emoji": "🇪🇷",
                "title": "Flag: Eritrea"
            },
            {
                "emoji": "🇪🇸",
                "title": "Flag: Spain"
            },
            {
                "emoji": "🇪🇹",
                "title": "Flag: Ethiopia"
            },
            {
                "emoji": "🇪🇺",
                "title": "Flag: European Union"
            },
            {
                "emoji": "🇫🇮",
                "title": "Flag: Finland"
            },
            {
                "emoji": "🇫🇯",
                "title": "Flag: Fiji"
            },
            {
                "emoji": "🇫🇰",
                "title": "Flag: Falkland Islands"
            },
            {
                "emoji": "🇫🇲",
                "title": "Flag: Micronesia"
            },
            {
                "emoji": "🇫🇴",
                "title": "Flag: Faroe Islands"
            },
            {
                "emoji": "🇫🇷",
                "title": "Flag: France"
            },
            {
                "emoji": "🇬🇦",
                "title": "Flag: Gabon"
            },
            {
                "emoji": "🇬🇧",
                "title": "Flag: United Kingdom"
            },
            {
                "emoji": "🇬🇩",
                "title": "Flag: Grenada"
            },
            {
                "emoji": "🇬🇪",
                "title": "Flag: Georgia"
            },
            {
                "emoji": "🇬🇫",
                "title": "Flag: French Guiana"
            },
            {
                "emoji": "🇬🇬",
                "title": "Flag: Guernsey"
            },
            {
                "emoji": "🇬🇭",
                "title": "Flag: Ghana"
            },
            {
                "emoji": "🇬🇮",
                "title": "Flag: Gibraltar"
            },
            {
                "emoji": "🇬🇱",
                "title": "Flag: Greenland"
            },
            {
                "emoji": "🇬🇲",
                "title": "Flag: Gambia"
            },
            {
                "emoji": "🇬🇳",
                "title": "Flag: Guinea"
            },
            {
                "emoji": "🇬🇵",
                "title": "Flag: Guadeloupe"
            },
            {
                "emoji": "🇬🇶",
                "title": "Flag: Equatorial Guinea"
            },
            {
                "emoji": "🇬🇷",
                "title": "Flag: Greece"
            },
            {
                "emoji": "🇬🇸",
                "title": "Flag: South Georgia & South Sandwich Islands"
            },
            {
                "emoji": "🇬🇹",
                "title": "Flag: Guatemala"
            },
            {
                "emoji": "🇬🇺",
                "title": "Flag: Guam"
            },
            {
                "emoji": "🇬🇼",
                "title": "Flag: Guinea-Bissau"
            },
            {
                "emoji": "🇬🇾",
                "title": "Flag: Guyana"
            },
            {
                "emoji": "🇭🇰",
                "title": "Flag: Hong Kong SAR China"
            },
            {
                "emoji": "🇭🇲",
                "title": "Flag: Heard & McDonald Islands"
            },
            {
                "emoji": "🇭🇳",
                "title": "Flag: Honduras"
            },
            {
                "emoji": "🇭🇷",
                "title": "Flag: Croatia"
            },
            {
                "emoji": "🇭🇹",
                "title": "Flag: Haiti"
            },
            {
                "emoji": "🇭🇺",
                "title": "Flag: Hungary"
            },
            {
                "emoji": "🇮🇨",
                "title": "Flag: Canary Islands"
            },
            {
                "emoji": "🇮🇩",
                "title": "Flag: Indonesia"
            },
            {
                "emoji": "🇮🇪",
                "title": "Flag: Ireland"
            },
            {
                "emoji": "🇮🇱",
                "title": "Flag: Israel"
            },
            {
                "emoji": "🇮🇲",
                "title": "Flag: Isle of Man"
            },
            {
                "emoji": "🇮🇳",
                "title": "Flag: India"
            },
            {
                "emoji": "🇮🇴",
                "title": "Flag: British Indian Ocean Territory"
            },
            {
                "emoji": "🇮🇶",
                "title": "Flag: Iraq"
            },
            {
                "emoji": "🇮🇷",
                "title": "Flag: Iran"
            },
            {
                "emoji": "🇮🇸",
                "title": "Flag: Iceland"
            },
            {
                "emoji": "🇮🇹",
                "title": "Flag: Italy"
            },
            {
                "emoji": "🇯🇪",
                "title": "Flag: Jersey"
            },
            {
                "emoji": "🇯🇲",
                "title": "Flag: Jamaica"
            },
            {
                "emoji": "🇯🇴",
                "title": "Flag: Jordan"
            },
            {
                "emoji": "🇯🇵",
                "title": "Flag: Japan"
            },
            {
                "emoji": "🇰🇪",
                "title": "Flag: Kenya"
            },
            {
                "emoji": "🇰🇬",
                "title": "Flag: Kyrgyzstan"
            },
            {
                "emoji": "🇰🇭",
                "title": "Flag: Cambodia"
            },
            {
                "emoji": "🇰🇮",
                "title": "Flag: Kiribati"
            },
            {
                "emoji": "🇰🇲",
                "title": "Flag: Comoros"
            },
            {
                "emoji": "🇰🇳",
                "title": "Flag: St. Kitts & Nevis"
            },
            {
                "emoji": "🇰🇵",
                "title": "Flag: North Korea"
            },
            {
                "emoji": "🇰🇷",
                "title": "Flag: South Korea"
            },
            {
                "emoji": "🇰🇼",
                "title": "Flag: Kuwait"
            },
            {
                "emoji": "🇰🇾",
                "title": "Flag: Cayman Islands"
            },
            {
                "emoji": "🇰🇿",
                "title": "Flag: Kazakhstan"
            },
            {
                "emoji": "🇱🇦",
                "title": "Flag: Laos"
            },
            {
                "emoji": "🇱🇧",
                "title": "Flag: Lebanon"
            },
            {
                "emoji": "🇱🇨",
                "title": "Flag: St. Lucia"
            },
            {
                "emoji": "🇱🇮",
                "title": "Flag: Liechtenstein"
            },
            {
                "emoji": "🇱🇰",
                "title": "Flag: Sri Lanka"
            },
            {
                "emoji": "🇱🇷",
                "title": "Flag: Liberia"
            },
            {
                "emoji": "🇱🇸",
                "title": "Flag: Lesotho"
            },
            {
                "emoji": "🇱🇹",
                "title": "Flag: Lithuania"
            },
            {
                "emoji": "🇱🇺",
                "title": "Flag: Luxembourg"
            },
            {
                "emoji": "🇱🇻",
                "title": "Flag: Latvia"
            },
            {
                "emoji": "🇱🇾",
                "title": "Flag: Libya"
            },
            {
                "emoji": "🇲🇦",
                "title": "Flag: Morocco"
            },
            {
                "emoji": "🇲🇨",
                "title": "Flag: Monaco"
            },
            {
                "emoji": "🇲🇩",
                "title": "Flag: Moldova"
            },
            {
                "emoji": "🇲🇪",
                "title": "Flag: Montenegro"
            },
            {
                "emoji": "🇲🇫",
                "title": "Flag: St. Martin"
            },
            {
                "emoji": "🇲🇬",
                "title": "Flag: Madagascar"
            },
            {
                "emoji": "🇲🇭",
                "title": "Flag: Marshall Islands"
            },
            {
                "emoji": "🇲🇰",
                "title": "Flag: North Macedonia"
            },
            {
                "emoji": "🇲🇱",
                "title": "Flag: Mali"
            },
            {
                "emoji": "🇲🇲",
                "title": "Flag: Myanmar (Burma)"
            },
            {
                "emoji": "🇲🇳",
                "title": "Flag: Mongolia"
            },
            {
                "emoji": "🇲🇴",
                "title": "Flag: Macao Sar China"
            },
            {
                "emoji": "🇲🇵",
                "title": "Flag: Northern Mariana Islands"
            },
            {
                "emoji": "🇲🇶",
                "title": "Flag: Martinique"
            },
            {
                "emoji": "🇲🇷",
                "title": "Flag: Mauritania"
            },
            {
                "emoji": "🇲🇸",
                "title": "Flag: Montserrat"
            },
            {
                "emoji": "🇲🇹",
                "title": "Flag: Malta"
            },
            {
                "emoji": "🇲🇺",
                "title": "Flag: Mauritius"
            },
            {
                "emoji": "🇲🇻",
                "title": "Flag: Maldives"
            },
            {
                "emoji": "🇲🇼",
                "title": "Flag: Malawi"
            },
            {
                "emoji": "🇲🇽",
                "title": "Flag: Mexico"
            },
            {
                "emoji": "🇲🇾",
                "title": "Flag: Malaysia"
            },
            {
                "emoji": "🇲🇿",
                "title": "Flag: Mozambique"
            },
            {
                "emoji": "🇳🇦",
                "title": "Flag: Namibia"
            },
            {
                "emoji": "🇳🇨",
                "title": "Flag: New Caledonia"
            },
            {
                "emoji": "🇳🇪",
                "title": "Flag: Niger"
            },
            {
                "emoji": "🇳🇫",
                "title": "Flag: Norfolk Island"
            },
            {
                "emoji": "🇳🇬",
                "title": "Flag: Nigeria"
            },
            {
                "emoji": "🇳🇮",
                "title": "Flag: Nicaragua"
            },
            {
                "emoji": "🇳🇱",
                "title": "Flag: Netherlands"
            },
            {
                "emoji": "🇳🇴",
                "title": "Flag: Norway"
            },
            {
                "emoji": "🇳🇵",
                "title": "Flag: Nepal"
            },
            {
                "emoji": "🇳🇷",
                "title": "Flag: Nauru"
            },
            {
                "emoji": "🇳🇺",
                "title": "Flag: Niue"
            },
            {
                "emoji": "🇳🇿",
                "title": "Flag: New Zealand"
            },
            {
                "emoji": "🇴🇲",
                "title": "Flag: Oman"
            },
            {
                "emoji": "🇵🇦",
                "title": "Flag: Panama"
            },
            {
                "emoji": "🇵🇪",
                "title": "Flag: Peru"
            },
            {
                "emoji": "🇵🇫",
                "title": "Flag: French Polynesia"
            },
            {
                "emoji": "🇵🇬",
                "title": "Flag: Papua New Guinea"
            },
            {
                "emoji": "🇵🇭",
                "title": "Flag: Philippines"
            },
            {
                "emoji": "🇵🇰",
                "title": "Flag: Pakistan"
            },
            {
                "emoji": "🇵🇱",
                "title": "Flag: Poland"
            },
            {
                "emoji": "🇵🇲",
                "title": "Flag: St. Pierre & Miquelon"
            },
            {
                "emoji": "🇵🇳",
                "title": "Flag: Pitcairn Islands"
            },
            {
                "emoji": "🇵🇷",
                "title": "Flag: Puerto Rico"
            },
            {
                "emoji": "🇵🇸",
                "title": "Flag: Palestinian Territories"
            },
            {
                "emoji": "🇵🇹",
                "title": "Flag: Portugal"
            },
            {
                "emoji": "🇵🇼",
                "title": "Flag: Palau"
            },
            {
                "emoji": "🇵🇾",
                "title": "Flag: Paraguay"
            },
            {
                "emoji": "🇶🇦",
                "title": "Flag: Qatar"
            },
            {
                "emoji": "🇷🇪",
                "title": "Flag: Réunion"
            },
            {
                "emoji": "🇷🇴",
                "title": "Flag: Romania"
            },
            {
                "emoji": "🇷🇸",
                "title": "Flag: Serbia"
            },
            {
                "emoji": "🇷🇺",
                "title": "Flag: Russia"
            },
            {
                "emoji": "🇷🇼",
                "title": "Flag: Rwanda"
            },
            {
                "emoji": "🇸🇦",
                "title": "Flag: Saudi Arabia"
            },
            {
                "emoji": "🇸🇧",
                "title": "Flag: Solomon Islands"
            },
            {
                "emoji": "🇸🇨",
                "title": "Flag: Seychelles"
            },
            {
                "emoji": "🇸🇩",
                "title": "Flag: Sudan"
            },
            {
                "emoji": "🇸🇪",
                "title": "Flag: Sweden"
            },
            {
                "emoji": "🇸🇬",
                "title": "Flag: Singapore"
            },
            {
                "emoji": "🇸🇭",
                "title": "Flag: St. Helena"
            },
            {
                "emoji": "🇸🇮",
                "title": "Flag: Slovenia"
            },
            {
                "emoji": "🇸🇯",
                "title": "Flag: Svalbard & Jan Mayen"
            },
            {
                "emoji": "🇸🇰",
                "title": "Flag: Slovakia"
            },
            {
                "emoji": "🇸🇱",
                "title": "Flag: Sierra Leone"
            },
            {
                "emoji": "🇸🇲",
                "title": "Flag: San Marino"
            },
            {
                "emoji": "🇸🇳",
                "title": "Flag: Senegal"
            },
            {
                "emoji": "🇸🇴",
                "title": "Flag: Somalia"
            },
            {
                "emoji": "🇸🇷",
                "title": "Flag: Suriname"
            },
            {
                "emoji": "🇸🇸",
                "title": "Flag: South Sudan"
            },
            {
                "emoji": "🇸🇹",
                "title": "Flag: São Tomé & Príncipe"
            },
            {
                "emoji": "🇸🇻",
                "title": "Flag: El Salvador"
            },
            {
                "emoji": "🇸🇽",
                "title": "Flag: Sint Maarten"
            },
            {
                "emoji": "🇸🇾",
                "title": "Flag: Syria"
            },
            {
                "emoji": "🇸🇿",
                "title": "Flag: Eswatini"
            },
            {
                "emoji": "🇹🇦",
                "title": "Flag: Tristan Da Cunha"
            },
            {
                "emoji": "🇹🇨",
                "title": "Flag: Turks & Caicos Islands"
            },
            {
                "emoji": "🇹🇩",
                "title": "Flag: Chad"
            },
            {
                "emoji": "🇹🇫",
                "title": "Flag: French Southern Territories"
            },
            {
                "emoji": "🇹🇬",
                "title": "Flag: Togo"
            },
            {
                "emoji": "🇹🇭",
                "title": "Flag: Thailand"
            },
            {
                "emoji": "🇹🇯",
                "title": "Flag: Tajikistan"
            },
            {
                "emoji": "🇹🇰",
                "title": "Flag: Tokelau"
            },
            {
                "emoji": "🇹🇱",
                "title": "Flag: Timor-Leste"
            },
            {
                "emoji": "🇹🇲",
                "title": "Flag: Turkmenistan"
            },
            {
                "emoji": "🇹🇳",
                "title": "Flag: Tunisia"
            },
            {
                "emoji": "🇹🇴",
                "title": "Flag: Tonga"
            },
            {
                "emoji": "🇹🇷",
                "title": "Flag: Turkey"
            },
            {
                "emoji": "🇹🇹",
                "title": "Flag: Trinidad & Tobago"
            },
            {
                "emoji": "🇹🇻",
                "title": "Flag: Tuvalu"
            },
            {
                "emoji": "🇹🇼",
                "title": "Flag: Taiwan"
            },
            {
                "emoji": "🇹🇿",
                "title": "Flag: Tanzania"
            },
            {
                "emoji": "🇺🇦",
                "title": "Flag: Ukraine"
            },
            {
                "emoji": "🇺🇬",
                "title": "Flag: Uganda"
            },
            {
                "emoji": "🇺🇲",
                "title": "Flag: U.S. Outlying Islands"
            },
            {
                "emoji": "🇺🇳",
                "title": "Flag: United Nations"
            },
            {
                "emoji": "🇺🇸",
                "title": "Flag: United States"
            },
            {
                "emoji": "🇺🇾",
                "title": "Flag: Uruguay"
            },
            {
                "emoji": "🇺🇿",
                "title": "Flag: Uzbekistan"
            },
            {
                "emoji": "🇻🇦",
                "title": "Flag: Vatican City"
            },
            {
                "emoji": "🇻🇨",
                "title": "Flag: St. Vincent & Grenadines"
            },
            {
                "emoji": "🇻🇪",
                "title": "Flag: Venezuela"
            },
            {
                "emoji": "🇻🇬",
                "title": "Flag: British Virgin Islands"
            },
            {
                "emoji": "🇻🇮",
                "title": "Flag: U.S. Virgin Islands"
            },
            {
                "emoji": "🇻🇳",
                "title": "Flag: Vietnam"
            },
            {
                "emoji": "🇻🇺",
                "title": "Flag: Vanuatu"
            },
            {
                "emoji": "🇼🇫",
                "title": "Flag: Wallis & Futuna"
            },
            {
                "emoji": "🇼🇸",
                "title": "Flag: Samoa"
            },
            {
                "emoji": "🇽🇰",
                "title": "Flag: Kosovo"
            },
            {
                "emoji": "🇾🇪",
                "title": "Flag: Yemen"
            },
            {
                "emoji": "🇾🇹",
                "title": "Flag: Mayotte"
            },
            {
                "emoji": "🇿🇦",
                "title": "Flag: South Africa"
            },
            {
                "emoji": "🇿🇲",
                "title": "Flag: Zambia"
            },
            {
                "emoji": "🇿🇼",
                "title": "Flag: Zimbabwe"
            },
            {
                "emoji": "🏴󠁧󠁢󠁥󠁮󠁧󠁿",
                "title": "Flag: England"
            },
            {
                "emoji": "🏴󠁧󠁢󠁳󠁣󠁴󠁿",
                "title": "Flag: Scotland"
            },
            {
                "emoji": "🏴󠁧󠁢󠁷󠁬󠁳󠁿",
                "title": "Flag: Wales"
            },
            {
                "emoji": "🏴󠁵󠁳󠁴󠁸󠁿",
                "title": "Flag for Texas (US-TX)"
            }
        ]
    };

    const categoryFlags = {
        'People': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"/> </g> </g> <g> <g> <path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368 c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99 c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166 c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z"/> </g> </g> <g> <g> <circle cx="168" cy="180.12" r="32"/> </g> </g> <g> <g> <circle cx="344" cy="180.12" r="32"/> </g> </g> <g> </g> <g> </g> <g> </g> </svg>',
        'Nature': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 354.968 354.968" style="enable-background:new 0 0 354.968 354.968;" xml:space="preserve"> <g> <g> <path d="M350.775,341.319c-9.6-28.4-20.8-55.2-34.4-80.8c0.4-0.4,0.8-1.2,1.6-1.6c30.8-34.8,44-83.6,20.4-131.6 c-20.4-41.6-65.6-76.4-124.8-98.8c-57.2-22-127.6-32.4-200.4-27.2c-5.6,0.4-10,5.2-9.6,10.8c0.4,2.8,1.6,5.6,4,7.2 c36.8,31.6,50,79.2,63.6,126.8c8,28,15.6,55.6,28.4,81.2c0,0.4,0.4,0.4,0.4,0.8c30.8,59.6,78,81.2,122.8,78.4 c18.4-1.2,36-6.4,52.4-14.4c9.2-4.8,18-10.4,26-16.8c11.6,23.2,22,47.2,30.4,72.8c1.6,5.2,7.6,8,12.8,6.4 C349.975,352.119,352.775,346.519,350.775,341.319z M271.175,189.319c-34.8-44.4-78-82.4-131.6-112.4c-4.8-2.8-11.2-1.2-13.6,4 c-2.8,4.8-1.2,11.2,4,13.6c50.8,28.8,92.4,64.8,125.6,107.2c13.2,17.2,25.2,35.2,36,54c-8,7.6-16.4,13.6-25.6,18 c-14,7.2-28.8,11.6-44.4,12.4c-37.6,2.4-77.2-16-104-67.6v-0.4c-11.6-24-19.2-50.8-26.8-78c-12.4-43.2-24.4-86.4-53.6-120.4 c61.6-1.6,120.4,8.4,169.2,27.2c54.4,20.8,96,52,114,88.8c18.8,38,9.2,76.8-14.4,105.2 C295.575,222.919,283.975,205.719,271.175,189.319z"/> </g> </g> <g> </g> <g> </g> <g> </g> </svg>',
        'Food-dring': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 295 295" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 295 295"> <g> <path d="M25,226.011v16.511c0,8.836,7.465,16.489,16.302,16.489h214.063c8.837,0,15.636-7.653,15.636-16.489v-16.511H25z"/> <path d="m271.83,153.011c-3.635-66-57.634-117.022-123.496-117.022-65.863,0-119.863,51.021-123.498,117.022h246.994zm-198.497-50.99c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm42,33c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm33.248-58c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm32.752,58c-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.693 8.25,8.25 0,4.557-3.693,8.25-8.25,8.25zm50.25-41.25c0,4.557-3.693,8.25-8.25,8.25-4.557,0-8.25-3.693-8.25-8.25 0-4.557 3.693-8.25 8.25-8.25 4.557,0 8.25,3.694 8.25,8.25z"/> <path d="m275.414,169.011h-0.081-254.825c-11.142,0-20.508,8.778-20.508,19.921v0.414c0,11.143 9.366,20.665 20.508,20.665h254.906c11.142,0 19.586-9.523 19.586-20.665v-0.414c0-11.143-8.444-19.921-19.586-19.921z"/> </g> </svg>',
        'Activity': '<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path id="XMLID_272_" d="m437.02 74.98c-48.353-48.351-112.64-74.98-181.02-74.98s-132.667 26.629-181.02 74.98c-48.351 48.353-74.98 112.64-74.98 181.02s26.629 132.667 74.98 181.02c48.353 48.351 112.64 74.98 181.02 74.98s132.667-26.629 181.02-74.98c48.351-48.353 74.98-112.64 74.98-181.02s-26.629-132.667-74.98-181.02zm-407.02 181.02c0-57.102 21.297-109.316 56.352-149.142 37.143 45.142 57.438 101.499 57.438 160.409 0 53.21-16.914 105.191-47.908 148.069-40.693-40.891-65.882-97.226-65.882-159.336zm88.491 179.221c35.75-48.412 55.3-107.471 55.3-167.954 0-66.866-23.372-130.794-66.092-181.661 39.718-34.614 91.603-55.606 148.301-55.606 56.585 0 108.376 20.906 148.064 55.396-42.834 50.9-66.269 114.902-66.269 181.872 0 60.556 19.605 119.711 55.448 168.158-38.077 29.193-85.665 46.574-137.243 46.574-51.698 0-99.388-17.461-137.509-46.779zm297.392-19.645c-31.104-42.922-48.088-95.008-48.088-148.309 0-59.026 20.367-115.47 57.638-160.651 35.182 39.857 56.567 92.166 56.567 149.384 0 62.23-25.284 118.665-66.117 159.576z"/></svg>',
        'Travel-places': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"> <g><g><path d="M846.5,153.5C939,246.1,990,369.1,990,500c0,130.9-51,253.9-143.5,346.5C753.9,939,630.9,990,500,990c-130.9,0-253.9-51-346.5-143.5C61,753.9,10,630.9,10,500c0-130.9,51-253.9,143.5-346.5C246.1,61,369.1,10,500,10C630.9,10,753.9,61,846.5,153.5z M803.2,803.2c60.3-60.3,100.5-135.5,117-217.3c-12.9,19-25.2,26-32.9-16.5c-7.9-69.3-71.5-25-111.5-49.6c-42.1,28.4-136.8-55.2-120.7,39.1c24.8,42.5,134-56.9,79.6,33.1c-34.7,62.8-126.9,201.9-114.9,274c1.5,105-107.3,21.9-144.8-12.9c-25.2-69.8-8.6-191.8-74.6-225.9c-71.6-3.1-133-9.6-160.8-89.6c-16.7-57.3,17.8-142.5,79.1-155.7c89.8-56.4,121.9,66.1,206.1,68.4c26.2-27.4,97.4-36.1,103.4-66.8c-55.3-9.8,70.1-46.5-5.3-67.4c-41.6,4.9-68.4,43.1-46.3,75.6C496,410.3,493.5,274.8,416,317.6c-2,67.6-126.5,21.9-43.1,8.2c28.7-12.5-46.8-48.8-6-42.2c20-1.1,87.4-24.7,69.2-40.6c37.5-23.3,69.1,55.8,105.8-1.8c26.5-44.3-11.1-52.5-44.4-30c-18.7-21,33.1-66.3,78.8-85.9c15.2-6.5,29.8-10.1,40.9-9.1c23,26.6,65.6,31.2,67.8-3.2c-57-27.3-119.9-41.7-185-41.7c-93.4,0-182.3,29.7-255.8,84.6c19.8,9.1,31,20.3,11.9,34.7c-14.8,44.1-74.8,103.2-127.5,94.9c-27.4,47.2-45.4,99.2-53.1,153.6c44.1,14.6,54.3,43.5,44.8,53.2c-22.5,19.6-36.3,47.4-43.4,77.8C91.3,658,132.6,739,196.8,803.2c81,81,188.6,125.6,303.2,125.6C614.5,928.8,722.2,884.2,803.2,803.2z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></g> </svg>',
        'Objects': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 461.977 461.977" style="enable-background:new 0 0 461.977 461.977;" xml:space="preserve"> <g> <path d="M398.47,248.268L346.376,18.543C344.136,8.665,333.287,0,323.158,0H138.821c-10.129,0-20.979,8.665-23.219,18.543 L63.507,248.268c-0.902,3.979-0.271,7.582,1.775,10.145c2.047,2.564,5.421,3.975,9.501,3.975h51.822v39.108 c-6.551,3.555-11,10.493-11,18.47c0,11.598,9.402,21,21,21c11.598,0,21-9.402,21-21c0-7.978-4.449-14.916-11-18.47v-39.108h240.587 c4.079,0,7.454-1.412,9.501-3.975C398.742,255.849,399.372,252.247,398.47,248.268z"/> <path d="M318.735,441.977h-77.747V282.388h-20v159.588h-77.747c-5.523,0-10,4.477-10,10c0,5.523,4.477,10,10,10h175.494 c5.522,0,10-4.477,10-10C328.735,446.454,324.257,441.977,318.735,441.977z"/> </g> <g> </g> <g> </g> <g> </g> </svg>',
        'Symbols': '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.487 30.486" style="enable-background:new 0 0 30.487 30.486;" xml:space="preserve"> <g> <path d="M28.866,17.477h-2.521V15.03h-2.56c0.005-2.8-0.304-5.204-0.315-5.308l-0.088-0.67L22.75,8.811 c-0.021-0.008-0.142-0.051-0.317-0.109l2.287-8.519L19,4.836L15.23,0.022V0l-0.009,0.01L15.215,0v0.021l-3.769,4.815L5.725,0.183 l2.299,8.561c-0.157,0.051-0.268,0.09-0.288,0.098L7.104,9.084l-0.088,0.67c-0.013,0.104-0.321,2.508-0.316,5.308h-2.56v2.446H1.62 l0.447,2.514L1.62,22.689h6.474c1.907,2.966,5.186,7.549,7.162,7.797v-0.037c1.979-0.283,5.237-4.838,7.137-7.79h6.474l-0.447-2.67 L28.866,17.477z M21.137,20.355c-0.422,1.375-4.346,6.949-5.907,7.758v0.015c-1.577-0.853-5.461-6.373-5.882-7.739 c-0.002-0.043-0.005-0.095-0.008-0.146l11.804-0.031C21.141,20.264,21.139,20.314,21.137,20.355z M8.972,15.062 c-0.003-1.769,0.129-3.403,0.219-4.298c0.98-0.271,3.072-0.723,6.065-0.78v-0.03c2.979,0.06,5.063,0.51,6.04,0.779 c0.09,0.895,0.223,2.529,0.219,4.298L8.972,15.062z"/> </g> <g> </g> <g> </g> <g> </g> </svg>',
        'Flags': '<svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill-rule="evenodd"><g id="037---Waypoint-Flag" fill-rule="nonzero" transform="translate(0 -1)"><path id="Shape" d="m59.0752 28.5054c-3.7664123-1.873859-7.2507049-4.2678838-10.3506-7.1118 1.5923634-6.0211307 2.7737841-12.14349669 3.5361-18.3248.1788-1.44-.623-1.9047-.872-2.0126-.7016942-.26712004-1.4944908-.00419148-1.8975.6293-5.4726 6.5479-12.9687 5.8008-20.9053 5.0054-7.9985-.8-16.2506-1.6116-22.3684 5.4114-.85552122-1.067885-2.26533581-1.5228479-3.5837-1.1565l-.1377.0386c-1.81412367.5095218-2.87378593 2.391025-2.3691 4.2065l12.2089 43.6891c.3541969 1.2645215 1.5052141 2.1399137 2.8184 2.1435.2677318-.0003961.5341685-.0371657.792-.1093l1.0683-.2984h.001c.7485787-.2091577 1.3833789-.7071796 1.7646969-1.3844635.381318-.677284.4779045-1.478326.2685031-2.2268365l-3.7812-13.5327c5.5066-7.0807 13.18-6.3309 21.2988-5.52 8.1094.81 16.4863 1.646 22.64-5.7129l.0029-.0039c.6044387-.7534187.8533533-1.7315007.6826-2.6822-.0899994-.4592259-.3932698-.8481635-.8167-1.0474zm-42.0381 29.7446c-.1201754.2157725-.3219209.3742868-.56.44l-1.0684.2983c-.4949157.1376357-1.0078362-.1513714-1.1465-.646l-12.2095-43.6895c-.20840349-.7523825.23089143-1.5316224.9825-1.7428l.1367-.0381c.12366014-.0348192.25153137-.0524183.38-.0523.63429117.0010181 1.19083557.4229483 1.3631 1.0334l.1083.3876v.0021l6.2529 22.3755 5.8468 20.9238c.0669515.2380103.0360256.4929057-.0859.708zm40.6329-27.2925c-5.4736 6.5459-12.9707 5.7974-20.9043 5.0039-7.9033-.79-16.06-1.605-22.1552 5.1558l-5.463-19.548-2.0643-7.3873c5.5068-7.0794 13.1796-6.3119 21.3045-5.5007 7.7148.7695 15.6787 1.5664 21.7373-4.7095-.7467138 5.70010904-1.859683 11.3462228-3.332 16.9033-.1993066.7185155.0267229 1.4878686.583 1.9844 3.1786296 2.9100325 6.7366511 5.3762694 10.5771 7.3315-.0213812.2768572-.1194065.5422977-.2831.7666z"/></g></g></svg>'
    };

    const icons = {
        search: '<svg style="fill: #646772;" version="1.1" width="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 487.95 487.95" style="enable-background:new 0 0 487.95 487.95;" xml:space="preserve"> <g> <g> <path d="M481.8,453l-140-140.1c27.6-33.1,44.2-75.4,44.2-121.6C386,85.9,299.5,0.2,193.1,0.2S0,86,0,191.4s86.5,191.1,192.9,191.1 c45.2,0,86.8-15.5,119.8-41.4l140.5,140.5c8.2,8.2,20.4,8.2,28.6,0C490,473.4,490,461.2,481.8,453z M41,191.4 c0-82.8,68.2-150.1,151.9-150.1s151.9,67.3,151.9,150.1s-68.2,150.1-151.9,150.1S41,274.1,41,191.4z"/> </g> </g> <g> </g> <g> </g> </svg>',
        close: '<svg style="height: 11px !important;" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg"><path d="M28.94,26,51.39,3.55A2.08,2.08,0,0,0,48.45.61L26,23.06,3.55.61A2.08,2.08,0,0,0,.61,3.55L23.06,26,.61,48.45A2.08,2.08,0,0,0,2.08,52a2.05,2.05,0,0,0,1.47-.61L26,28.94,48.45,51.39a2.08,2.08,0,0,0,2.94-2.94Z"/></svg>',
        move: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.006 512.006" xml:space="preserve"> <g> <g> <path d="M508.247,246.756l-72.457-72.465c-5.009-5.009-13.107-5.009-18.116,0c-5.009,5.009-5.009,13.107,0,18.116l50.594,50.594 H268.811V43.748l50.594,50.594c5.009,5.009,13.107,5.009,18.116,0c5.009-5.009,5.009-13.107,0-18.116L265.056,3.761 c-5.001-5.009-13.107-5.009-18.116,0l-72.457,72.457c-5.009,5.009-5.009,13.107,0,18.116c5.001,5.009,13.107,5.009,18.116,0 l50.594-50.594v199.27H43.744l50.594-50.594c5.009-5.009,5.009-13.107,0-18.116c-5.009-5.009-13.107-5.009-18.116,0L3.757,246.756 c-5.009,5.001-5.009,13.107,0,18.116l72.465,72.457c5.009,5.009,13.107,5.009,18.116,0c5.009-5.001,5.009-13.107,0-18.116 l-50.594-50.594h199.458v199.646l-50.594-50.594c-5.009-5.001-13.107-5.001-18.116,0c-5.009,5.009-5.009,13.107,0,18.116 l72.457,72.465c5,5,13.107,5,18.116,0l72.465-72.457c5.009-5.009,5.009-13.107,0-18.116c-5.009-5-13.107-5-18.116,0 l-50.594,50.594V268.627h199.458l-50.594,50.594c-5.009,5.009-5.009,13.107,0,18.116s13.107,5.009,18.116,0l72.465-72.457 C513.257,259.872,513.257,251.765,508.247,246.756z"/> </g> </g> <g> </g> </svg>'
    }




    const functions = {

        styles: () => {

            const styles = `
                <style>
                    .fg-emoji-container {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: ${pickerWidth}px;
                        height: ${pickerHeight}px;
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
                        background-color: ${this.options.specialButtons ? this.options.specialButtons : '#ed5e28'};
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
            `;

            document.head.insertAdjacentHTML('beforeend', styles);
        },


        position: () => {

            const e             = window.event;
            const clickPosX     = e.clientX;
            const clickPosY     = e.clientY;
            const obj           = {};

            obj.left            = clickPosX;
            obj.top             = clickPosY;

            return obj;

        },


        rePositioning: (picker) => {
            picker.getBoundingClientRect().right > window.screen.availWidth ? picker.style.left = window.screen.availWidth - picker.offsetWidth + 'px' : false;

            if (window.innerHeight > pickerHeight) {
                picker.getBoundingClientRect().bottom > window.innerHeight ? picker.style.top = window.innerHeight - picker.offsetHeight + 'px' : false;
            }
        },


        render: (e, attr) => {

            emojiList = undefined;

            const index = this.options.trigger.findIndex(item => item.selector === attr);
            this.insertInto = this.options.trigger[index].insertInto;

            const position = functions.position();

            if (!emojiesHTML.length) {

                for (const key in emojiObj) {
                    if (emojiObj.hasOwnProperty.call(emojiObj, key)) {
                        const categoryObj = emojiObj[key];


                        categoriesHTML += `<li>
                            <a title="${key}" href="#${key}">${categoryFlags[key]}</a>
                        </li>`;

                        emojiesHTML += `<div class="fg-emoji-picker-category-wrapper" id="${key}">`;
                        emojiesHTML += `<p class="fg-emoji-picker-category-title">${key}</p>`;
                        categoryObj.forEach(ej => {
                            emojiesHTML += `<li data-title="${ej.title.toLowerCase()}">
                                    <a title="${ej.title}" href="#">${ej.emoji}</a>
                                </li>`;
                        });
                        emojiesHTML += '</div>';
                    }
                }
            }


            if (document.querySelector('.fg-emoji-container')) {
                this.lib('.fg-emoji-container').remove();
            }


            const picker = `
                <div class="fg-emoji-container" style="left: ${position.left}px; top: ${position.top}px;">
                    <nav class="fg-emoji-nav">
                        <ul>
                            ${categoriesHTML}

                            <li class="fg-picker-special-buttons" id="fg-emoji-picker-move"><a class="fg-emoji-picker-move" href="#">${icons.move}</a></li>
                            ${this.options.closeButton ? `<li class="fg-picker-special-buttons"><a id="fg-emoji-picker-close-button" href="#">`+icons.close+`</a></li>` : ''}
                        </ul>
                    </nav>

                    <div class="fg-emoji-picker-search">
                        <input type="text" placeholder="Search" autofocus />
                        
                        <span class="fg-emoji-picker-search-icon">${icons.search}</sapn>
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
                            ${emojiesHTML}
                        </ul>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', picker);

            functions.rePositioning(document.querySelector('.fg-emoji-container'));

            setTimeout(() => {
                document.querySelector('.fg-emoji-picker-search input').focus();
            }, 500)
        },


        closePicker: (e) => {

            e.preventDefault();

            this.lib('.fg-emoji-container').remove();

            moseMove = false;
        },


        checkPickerExist(e) {

            if (document.querySelector('.fg-emoji-container') && !e.target.closest('.fg-emoji-container') && !moseMove) {

                functions.closePicker.call(this, e);
            }
        },


        setCaretPosition: (field, caretPos) => {
            var elem = field
            if (elem != null) {
                if (elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                } else {
                    if (elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    } else {
                        elem.focus();
                    }
                }
            }
        },


        insert: e => {

            e.preventDefault();

            const emoji = e.target.innerText.trim();
            const myField = document.querySelectorAll(this.insertInto);
            const myValue = emoji;

            // Check if selector is an array
            myField.forEach(myField => {

                if (document.selection) {
                    myField.focus();
                    sel = document.selection.createRange();
                    sel.text = myValue;
                } else if (myField.selectionStart || myField.selectionStart == "0") {
                    const startPos = myField.selectionStart;
                    const endPos = myField.selectionEnd;
                    myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);

                    functions.setCaretPosition(myField, startPos + 2)

                } else {
                    myField.value += myValue;
                    myField.focus()
                }

                myField.dispatchEvent(new InputEvent('input'));
                if (this.options.closeOnSelect) {
                    functions.closePicker.call(this, e);
                }

            })
        },


        categoryNav: e => {
            e.preventDefault();

            const link          = e.target.closest('a');

            if (link.getAttribute('id') && link.getAttribute('id') === 'fg-emoji-picker-close-button') return false;
            if (link.className.includes('fg-emoji-picker-move')) return false;

            const id            = link.getAttribute('href');
            const emojiBody     = document.querySelector('.fg-emoji-list');
            const destination   = emojiBody.querySelector(`${id}`);

            this.lib('.fg-emoji-nav li').removeClass('emoji-picker-nav-active');
            link.closest('li').classList.add('emoji-picker-nav-active');

            destination.scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"})
        },


        search: e => {

            const val = e.target.value.trim();

            if (!emojiList) {
                emojiList = Array.from(document.querySelectorAll('.fg-emoji-picker-category-wrapper li'));
            }

            emojiList.filter(emoji => {
                if (!emoji.getAttribute('data-title').match(val)) {
                    emoji.style.display = 'none'
                } else {
                    emoji.style.display = ''
                }
            })
        },


        mouseDown: e => {
            e.preventDefault();
            moseMove = true;
        },

        mouseUp: e => {
            e.preventDefault();
            moseMove = false;
        },

        mouseMove: e => {

            if (moseMove) {
                e.preventDefault();
                const el = document.querySelector('.fg-emoji-container');
                el.style.left = e.clientX - 320 + 'px';
                el.style.top = e.clientY - 10 + 'px';
            }
        }
    };



    const bindEvents = () => {

        this.lib(document.body).on('click', functions.closePicker, '#fg-emoji-picker-close-button');
        this.lib(document.body).on('click', functions.checkPickerExist);
        this.lib(document.body).on('click', functions.render, this.trigger);
        this.lib(document.body).on('click', functions.insert, '.fg-emoji-list a');
        this.lib(document.body).on('click', functions.categoryNav, '.fg-emoji-nav a');
        this.lib(document.body).on('input', functions.search, '.fg-emoji-picker-search input');
        this.lib(document).on('mousedown', functions.mouseDown, '#fg-emoji-picker-move');
        this.lib(document).on('mouseup', functions.mouseUp, '#fg-emoji-picker-move');
        this.lib(document).on('mousemove', functions.mouseMove);
    };



    (() => {

        // Start styles
        functions.styles();

        // Event functions
        bindEvents.call(this);

    })()
}


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






