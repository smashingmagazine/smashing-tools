# How to us the Wallpaper selection tool #

## First step, gather all submissions and make them conform ##

You will get the submission either as an attachment from the submission email or as a link provided in the email.

Submissions will likely not be well formated at first, as many people don't want to follow guidelines. It's not their fault. The guidelines might be hard to understand at first. That's why they have to be educated. The following process would probably work flawlessly:

- stop accepting submissions up to five days before the end of the month (and don't accept any further submissions);
- start the publication process on D-5, find out which submissions are good or bad, next send a nice and respectful email to the people who made a bad submission telling them that they are dismissed. Dismissed unless they can provide a better submission in the next five days —don't forget to mention that this won't necessarily means that they will be selected;
- select the good ones, reject the bad ones;
- publish the wallpapers;
- redo those steps until everyone submitting is educated.

You will have to make the submissions manually fit.

- Three things must be present in the root directory of the archive: `cal/` and `nocal` directories, as well as the form file (either `form` or `form.json`).
- Calendars are stored in the `cal` subdirectory.
- Non calendars are stored in the `nocal` subdirectory.
- Calendars follow this naming convention: `AxB.C`, were `A` is the width of the wallpaper (like `1280`) and `B` is the height (like `720`). `C` is the file format (`png` or `jpg`). Please not that the separating character is a minuscule `x`, nothing else will work.

At the end, you will have lots of directories and in each of them a proper submission (`cal/`, `nocal/` and `form`).

## Upload the wallpapers to the selection tools ##

You can use your favourite FTP client for this part. Ask your favourite front-end dev the needed credentials. You will have to get to `/playground.smashingmagazine.com/wallpaper-selection/shared/source_wallpapers` and upload all the submissions. Be careful once they are uploaded, you will need to give the proper permissions to the files (755). Reuqest the *group* and the *others* flags to have the **read**, and **execute** permissions. This is important, don't forget this part or the tool will have troubles reading some files.

This is needed since we can't know from which environment nor with which permissions the files were created.

## Run the tool ##

Go to [http://playground.smashingmagazine.com/wallpaper-selection/](http://playground.smashingmagazine.com/wallpaper-selection/), here you can manipulate the submissions. Before every new submission, be sure to press the red trash, in order to cleanup the last wallpapers. Then, you'll need to press the synchronization blue button. Some warnings might display at this point if the tool encountered a problem while parsing the submissions. Be sure to read them and to correct them.

- A form error is most likely linked to a badly written form file, a bad file format, a missing form or a permission problem on the form file.
- A name error is likely due to a bad naming scheme for the wallpapers (like `1234X766` instead of `1234x766`).

## Gather the code and the generated images ##

You will then need to go to `/playground.smashingmagazine.com/wallpaper-selection/shared/wallpapers` and download the generated wallpapers and then reupload them to `files.smashingmagazine.com`. You can also download the `wallpapers` directory, rename it to `<month in three letters>-<year in two digits>` (e. g. `oct-13`) and upload them to `files.smashingmagazine.com`, as you will have to create the month directory anyway.
    
To gather the code, just click on the black download button inside the tool.