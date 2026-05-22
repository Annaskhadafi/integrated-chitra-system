cls
@ECHO OFF
title Folder product
if EXIST "Ajax.{21EC2020-3AEA-1069-A2DD-08002B30309D}" goto UNLOCK
if NOT EXIST product goto MDLOCKER
:CONFIRM
echo Are you sure u want to Lock the folder(Y/N)
set/p "cho=>"
if %cho%==Y goto LOCK
if %cho%==y goto LOCK
if %cho%==n goto END
if %cho%==N goto END
echo Invalid choice.
pause
goto CONFIRM
:LOCK
ren product "Ajax.{21EC2020-3AEA-1069-A2DD-08002B30309D}"
ren build "Control Panel.{208002B3-3AEA-1069-A2DD-1EC20200309D}"
ren documentation "System64.{0309D020-B321EC2}"
attrib +h "Ajax.{21EC2020-3AEA-1069-A2DD-08002B30309D}"
attrib +h "Control Panel.{208002B3-3AEA-1069-A2DD-1EC20200309D}"
attrib +h "System64.{0309D020-B321EC2}"
echo Folder locked
goto End
:UNLOCK
echo Enter password to Unlock folder
set/p "pass=>"
if NOT %pass%==ctschitraparatama goto FAIL
attrib -h "Ajax.{21EC2020-3AEA-1069-A2DD-08002B30309D}"
attrib -h "Control Panel.{208002B3-3AEA-1069-A2DD-1EC20200309D}"
attrib -h "System64.{0309D020-B321EC2}"
ren "Ajax.{21EC2020-3AEA-1069-A2DD-08002B30309D}" product
ren "Control Panel.{208002B3-3AEA-1069-A2DD-1EC20200309D}" build
ren "System64.{0309D020-B321EC2}" documentation
echo Folder Unlocked successfully
goto End
:FAIL
echo Invalid password
pause
goto end
:MDLOCKER
md product
echo product created successfully
pause
goto End
:End