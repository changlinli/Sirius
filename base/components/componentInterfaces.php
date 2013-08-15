<?php
/**
 * @file componentInterfaces.php The interfaces for the various classes in the 
 * component layer of the R3L platform.
 *
 * There are two methods for allowing a single class to reap the benefits of 
 * multiple interfaces. One is to simply implement multiple interfaces, e.g.
 *
 *     testClass implements interface1, interface2 {
 *         // Stuff goes here
 *     }
 * 
 * or we can use inheritance, e.g.
 *     
 *     interface2 extends interface1 {
 *         // Stuff goes here
 *     }
 *
 *     testClass implements interface2 {
 *         // Even more stuff goes here
 *     }
 *
 * The first case, multiple interfaces, should be preferred if each of the 
 * interfaces used are independent. That is it is sufficient to use all the 
 * behavior of `interface1` simply via the methods that interface1 provides. 
 * None of `interface2`'s methods are required to make `interface1` work. 
 *
 * The second case, inheritance, should be preferred if interface2 induces 
 * additional constraints on `interface1`. For example, a web API will use all 
 * the methods of a CRUD interface, but it may also have a set of methods in 
 * `interface2` which allow the client to verify itself to the API. Without 
 * using these methods in `interface2` it is impossible to use `interface1`'s 
 * methods successfully.
 */
namespace r3l_platform\base\components\componentInterfaces;

/**
 * basic_crud_interface: The interface for CRUD operations that all components 
 * should support to some degree. This support, at minimum, should entail 
 * raising an exception  if an unsupported method is used. For example, if we have a 
 * web resource represented by web_resource which does not support any form of 
 * delete, then running web_resource->delete($update_params) should raise an
 * exception that delete is not a supported operation.
 */
interface BasicCRUDInterface {
	/**
	 * upsert: A function that creates (e.g. inserts in the case of a 
	 * database) a new record if either an id element is not specified or if 
	 * the id is not currently held by the resource and updates a 
	 * pre-existing record if the id is currently held by the resource, 
	 * hence "upsert." For example a database might have rows of users. A 
	 * user->upsert(array('id'=>2, 'blah'=>3)) will update the user with an 
	 * id of 2 if such a user exists, otherwise it will create such a user.
     *
     * @param array $upsert_params The parameters which we wish to pass to our 
     * underlying data model. These parameters act as both an identifier of the 
     * resource to be updated as well as an identifier of the fields to be 
     * updated. The input array should be of the form.
     * 
	 * @return false if the operation was not able to update nor was able to 
	 * create a new resource. Return the id of the created resource 
	 * otherwise.
	 */
	public function upsert(array $upsert_params);
	/**
	 * create: A function that creates (e.g. inserts in the case of a 
	 * database) a new record. Note that this function may fail if the record 
     * already exists (instead of updating). In order to be guaranteed that a 
     * creation command can also update if the ID already exists, use upsert if 
     * it is supported by the implementation.
     *
     * @param array $creation_params The parameters of the object we wish to 
     * create. For more information see BasicCRUDInterface::upsert.
     *
     * @return boolean false if the operation was not able to create a new resouce. 
     * Return the id of the created resource otherwise.
	 */
	public function create(array $creation_params);
	public function read(array $select_params);
	public function update(array $update_params);
	public function delete(array $delete_params);
}

/**
 * initialization_interface: The interface for CRUD operations which require an 
 * additional initialization step beforehand. This is generally the case for web 
 * APIs or other 3rd-party plugins.
 */
interface InitializationInterface extends BasicCRUDInterface {
	public function initialize(array $init_params);
}
