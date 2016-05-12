# Contao 3 - error.log enhanced

Contao error.log with complete relative file paths.

# Example

Original error stack on not a valid JPEG file:

```
#0 [internal function]: __error(2, 'imagecreatefrom...', '/contao35_deve...', 82, Array)
#1 /contao35_develop/system/modules/core/library/Contao/GdImage.php(82): imagecreatefromjpeg('/daten/contao2g...')
#2 /contao35_develop/system/modules/core/library/Contao/Image.php(541): Contao\GdImage::fromFile(Object(Contao\File))
#3 /contao35_develop/system/modules/core/library/Contao/Image.php(510): Contao\Image->executeResizeGd()
#4 /contao35_develop/system/modules/core/library/Contao/Image.php(950): Contao\Image->executeResize()
#5 /contao35_develop/system/modules/core/classes/DataContainer.php(516): Contao\Image::get('files/contaodem...', 699, 524, 'box')
#6 /contao35_develop/system/modules/core/drivers/DC_Folder.php(1221): Contao\DataContainer->row()
#7 /contao35_develop/system/modules/core/classes/Backend.php(650): Contao\DC_Folder->edit()
#8 /contao35_develop/system/modules/core/controllers/BackendMain.php(131): Contao\Backend->getBackendModule('files')
#9 /contao35_develop/contao/main.php(20): Contao\BackendMain->run()
#10 {main}
```

**Problem:** how is the name of the not valid JPEG file?

Now, the new log:

```
#0 [internal function]: __error_enhanced(2, 'imagecreatefromjpeg(): 'files/contaodemo/media/demo-wrong-image.jpg' is not a valid JPEG file', 'system/modules/core/library/Contao/GdImage.php', 82, Array)
#1 system/modules/core/library/Contao/GdImage.php(82): imagecreatefromjpeg('files/contaodemo/media/demo-wrong-image.jpg')
#2 system/modules/core/library/Contao/Image.php(541): Contao\GdImage::fromFile(Contao\File)
#3 system/modules/core/library/Contao/Image.php(510): Contao\Image->executeResizeGd()
#4 system/modules/core/library/Contao/Image.php(950): Contao\Image->executeResize()
#5 system/modules/core/classes/DataContainer.php(516): Contao\Image::get('files/contaodemo/media/demo-wrong-image.jpg', 699, 524, 'box')
#6 system/modules/core/drivers/DC_Folder.php(1221): Contao\DataContainer->row()
#7 system/modules/core/classes/Backend.php(650): Contao\DC_Folder->edit()
#8 system/modules/core/controllers/BackendMain.php(131): Contao\Backend->getBackendModule('files')
#9 contao/main.php(20): Contao\BackendMain->run()
```

**Answer:** `files/contaodemo/media/demo-wrong-image.jpg`, yeah!
