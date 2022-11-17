#if (${NAME} == "__construct")
    #set ($METHOD_NAME = "Constructor")
#elseif (${NAME} == "__destruct")
    #set ($METHOD_NAME = "Destructor")
#elseif (${NAME} == "__toString")
    #set ($METHOD_NAME = "toString")
#elseif (${NAME} == "__clone")
    #set ($METHOD_NAME = "Clone")
#elseif (${NAME} == "__invoke")
    #set ($METHOD_NAME = "Invoke")
#else
    #set ($METHOD_NAME = ${CAPITALIZED_NAME})
#end
/**
 * @return void
 *
 * @covers ::${NAME}
 */
public function test$METHOD_NAME(): void
{
#if (${NAME} == "__construct")
    $this->assertInstanceOf(
        ${TESTED_NAME}::class,
        new ${TESTED_NAME}()
    );
#else
    $subject = new ${TESTED_NAME}();
    $subject->${NAME}();
#end
}
