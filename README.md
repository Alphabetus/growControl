# GROW CONTROL

## WEB APP SYSTEM STATUS >> [UNRELEASED]:
- **USERS:** ![Progress](http://progressed.io/bar/55)
- **GROWS:** ![Progress](http://progressed.io/bar/99)
- **PLANTS:** ![Progress](http://progressed.io/bar/0)
- **GALLERY:** ![Progress](http://progressed.io/bar/0)  
- **OVERALL:** ![Progress](http://progressed.io/bar/35)

## What is this?

This is an anonymous and open-sourced, non intrusive, multi-user grow log system.
Once released you will be able to keep accurate track of many different aspects of your grows, such as temperature, humidity, last watering time, etc.

Different aspects of your log will be configurable and you will be able to pick which functions you want to activate within our log pool options when setting up your grow. Settings will be defined per grow, making the app dynamic and adjustable to any growing culture you are aiming to.

As a user you will be able to add as many grows as you need and assign as many plants you want to any grow. Anytime. Anywhere.

The objective is to provide the final user the ability to use the webApp online or locally on his own web server.

### What kind of data is required by the registration system? What info does the server reads from my device?

Grow Control is built around the idea of minimal data collection.
The required data to register on the platform, as it is coded, is a username and password.
No additional data is demanded or required at any time.

Due to this fact account recovery is not planned at this stage.
If you loose or forget your credentials you are done.

This is like a little secret box. Your own _DaVinci's Cryptex_.
(I **might** add a recovery system later based on security questions or a unique key given upon account creation.. lets see.)


**Note:**
To keep the user interface and usage optimal, the system reads and immediately encrypts your ip address and browser used to access.
This data is kept on the database **only while you are logged in**.
As soon as you press the `logout button` the system immediately deletes any trace of your presence.
It does not keep your ip or browser information as more than it is extremely necessary.

### Different grow types and status:

Grows are the definition used for a group of plants.
Segregate your plant index within different grows according to their age, characteristics, colours, whatever you want.
The organisation is yours.

Grows can be created as `indoor` or `outdoors` and can be defined as `active` or `inactive`.

### Different plant stages and status:

The plant lifespan is divided into 4 stages to better organise your garden.
The main definition should be seen as a guidance tip and not a required parameter.

1. **Seedling stage;**
      - Covers the germination time before the plant unfolds and gets exposed to the light.
      - It is triggered manually by the user.

2. **Vegetative stage;**
      - Covers the entire growing process of the plant. It ends when the flowering stage starts and should be visible on naked eye.
      - It is triggered manually by the user.

3. **Flora stage;**
      - Covers the period of time since the appearance of flower/fruit until the harvesting time.
      - This stage can occur automatically or it can be forced.
      - It is triggered manually by the user.

4. **Harvesting stage;**
      - This stage should cover all the remaining cycle of the plant.
      - Useful to keep track of drying and curation times.

Apart from the referred stages, plants also have different status such as `alive`, `dead`, etc.
If a plant dies you can define it as dead and store or delete it.


### LIVE GROW CONTROL:
- https://growcontrol.followarmy.com/
