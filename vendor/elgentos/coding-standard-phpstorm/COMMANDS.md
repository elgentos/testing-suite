# coding-standard-phpstorm

These commando's can be used in xml, php and phtml files.

## M2 XML
All xml templates make use of the includes "M2 XML File Header.xml".
All xml files also have appropriated xsd paths.

Available templates:
* M2 db schema xml:
* M2 di xml:
* M2 extension attributes xml:
* M2 layout xml:
* M2 module xml:

## M2 PHP Classes
All php files are PHP 7.1 or higher. These files have strict_types=1 on top of the templates and
make use of includes "M2 PHP File Header.php". 

Make use of `new > file > PHP Class` and select a different template all PHP templates start with M2.
If the one mentioned below is not present go to `file > settings > Editor -> File and Code Templates` and enable the template you're searching for.
This way Namespaces and vendor will be automatically filled in.

Available templates:
* **M2 class**: Default M2 php class.
* **M2 class - Backend Controller**: PHP class that extends AbstractAction.
* **M2 class - Block**: PHP class that extends Template class.
* **M2 class - Helper**: PHP class that extends AbstractHelper
* **M2 class - Observer**: PHP class that implements ObserverInterface
* **M2 class - ViewModel**: PHP class that implements ArgumentInterface
    
## M2 ACL
Available templates:
* **M2 Acl XML**: Create an acl template that already has path to backend menu and backend config (system.xml)

Available commands:
* **m2aclresource** -> Create a resource tag, available params
    * Vendor: vendor name
    * namespace: name of module
    * resourceId: acl id
    * title: acl title
    * order: sort order

## M2 Config
Available templates:
* **M2 Config XML**: Create an config with default structure.

Available commands:
* None

## M2 DB Schema
Available templates:
* **M2 Db schema XML**: Create an db schema with default structure.

Available commands:
* **m2dbtable**: Create an table.
* **m2dbcolumnvarchar**: Create an column as a varchar.
* **m2dbcolumnint**: Create an column as a int.
* **m2dbcolumndecimal**: Create an column decimal.
* **m2dbforeign**: Create an foreign key index.
* **m2dbcolumncreate**: Create an create created_at column.
* **m2dbindex**: Create an index.
* **m2dbcolumnupdate**: Create an updated_at column.

## M2 DI
Available templates:
* **M2 DI**: Create an DI file.

Available commands:
* **m2diplugin**: Create an plugin tyoe.
* **m2divirtual**: Create an virtual type
* **m2dipreference**: Create an preference(rewrite)

## M2 Events
Available templates:
* **M2 Events**: Create an events file.

Available commands:
* **m2eventobserver**: Create an observer

## M2 Extension Attribute
Available templates:
* **M2 Extension Attributes XML**: Create an extension attribute file with default structure.

## M2 Layout
Available templates:
* **M2 Layout XML**: Create an layout XML with default structure

## M2 Menu
Available templates:
* **M2 Menu XML**: Create an layout XML with default structure

Available commands:
* **m2menuadd**: Create an menu tag

## M2 Module
Available templates:
* **M2 Module XML**: Create an module XML file that has default structure

Available commands:
* **m2mod**: Create an module tag
* **m2modsequence**: Create an sequence tag

## M2 Registration
Available templates:
* **M2 Module XML**: Create an registration file with the method to register a m2 module

Available commands:
* **m2reg**: Create registration file

## M2 Sales
Available templates:
* **M2 Sales XML**: Create an sales xml file for registrating new totals.

Available commands:
* None

## M2 System / System Include
Available templates:
* **M2 System XML**: Create an system.xml with default structure.
* **M2 System Include XML**: Create an system include that can be included in a system.xml with default structure. 

Available commands:
* **m2syssection**: Create an section tag.
* **m2sysinclude**: Create an include tag.
* **m2sysgroup**: Create an group tag.
* **m2sysfieldcmspage**: Create a dropdown field with all cms pages.
* **m2sysfieldyesno**: Create a dropdown with yes and no.
* **m2sysfieldpagelayout**: Create a dropdown with all page layouts.
* **m2sysfieldenabledisable**: Create a dropdown with enable/disable.
* **m2sysfieldpassword**: Create an obscure password field.
* **m2sysfieldtext**: Create a text field.
* **m2sysfieldtextarea**: Create a textarea field.
* **m2sysfieldpricetype**: Create a dropdown with all price types.
* **m2sysfieldproducttax**: Create a dropdown with all product taxes.
* **m2sysfieldcountry**: Create a dropdown with all countries.
