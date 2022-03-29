**sources** <br>
https://designpatternsphp.readthedocs.io/en/latest/README.html <br>
https://refactoring.guru/design-patterns

https://levelup.gitconnected.com/getting-started-with-design-patterns-in-php-4d451ccdfb71
https://betterprogramming.pub/decorator-c04fae63dfff

<em>Aggregation</em>: object A contains object B; B can live without A<br>
<em>Composition</em>: object A consists of objects B; A manages life cycle of B; B can’t live without A

## Creational (5) 
>definesc mecanisme de creare a obiectelor

Creational patterns are ones that create objects, rather than having to instantiate objects directly. 
This gives the program more flexibility in deciding which objects need to be created for a given case.

**Abstract factory** groups object factories that have a common theme.<br>
**Builder** constructs complex objects by separating construction and representation.<br>
**Factory method** creates objects without specifying the exact class to create.<br>
**Prototype** creates objects by cloning an existing object.<br>
**Singleton** restricts object creation for a class to only one instance. <br>
http://elf.cs.pub.ro/poo/laboratoare/static-final#singleton-pattern

## Structural (7)
> definesc relații între entități

These concern class and object composition. 
They use inheritance to compose interfaces and define ways to compose objects to obtain new functionality.

**Adapter** allows classes with incompatible interfaces to work together by wrapping its own interface around that of an already existing class.<br>
**Bridge** decouples an abstraction from its implementation so that the two can vary independently.<br>
**Composite** composes zero-or-more similar objects so that they can be manipulated as one object.<br>
**Decorator** dynamically adds/overrides behaviour in an existing method of an object.<br>
**Facade** provides a simplified interface to a large body of code.<br>
**Flyweight** reduces the cost of creating and manipulating a large number of similar objects.<br>
**Proxy** provides a placeholder for another object to control access, reduce cost, and reduce complexity.<br>

## Behavioral (11)
> definesc comunicarea între entități

Most of these design patterns are specifically concerned with communication between objects.

**Chain of responsibility** delegates commands to a chain of processing objects.<br>
**Command** creates objects that encapsulate actions and parameters.<br>
**Interpreter** implements a specialized language.<br>
**Iterator** accesses the elements of an object sequentially without exposing its underlying representation.<br>
**Mediator** allows loose coupling between classes by being the only class that has detailed knowledge of their methods.<br>
**Memento** provides the ability to restore an object to its previous state (undo).<br>
**Observer** is a publish/subscribe pattern, which allows a number of observer objects to see an event.<br>
**State** allows an object to alter its behavior when its internal state changes.<br>
**Strategy** allows one family of algorithms to be selected on-the-fly at runtime.<br>
**Template method** defines the skeleton of an algorithm as an abstract class, allowing its subclasses to provide concrete behavior.<br>

**Visitor** separates an algorithm from an object structure by moving the hierarchy of methods into one object.
http://elf.cs.pub.ro/poo/laboratoare/visitor#visitor



## A. CREATIONAL

### FACTORY METHOD
> Factory method is a creational design pattern which solves the problem of creating type objects without specifying their concrete classes.\
  Factory Method defines a method, which should be used for creating objects instead of direct constructor call (new operator). Subclasses can override this method to change the class of objects that will be created.

- Define an interface for creating an object, but let the subclasses decide which class to instantiate.
  The Factory method delegates the real instantiation to subclasses
- produces one single type of objects (implementing the same interface)


**Applicability**
- Use the Factory Method when you don’t know beforehand the exact types and dependencies of the objects your code should work with.
- Use the Factory Method when you want to provide users of your library or framework with a way to extend its internal components.
- Use the Factory Method when you want to save system resources by reusing existing objects instead of rebuilding them each time.

**Identification** Factory methods can be recognized by creation methods, which create objects from concrete classes, but return them as objects of abstract type or interface.


### ABSTRACT FACTORY or FactoryFactoryPattern
> Abstract Factory is a creational design pattern that lets you produce families of related objects without specifying their concrete classes.

**Applicability**
- pretty rare to be used in a real simple project
- Use the Abstract Factory when your code needs to work with various families of related products, but you don’t want it to depend on the concrete classes of those products—they might be unknown beforehand or you simply want to allow for future extensibility.
  - Consider implementing the Abstract Factory when you have a class with a set of Factory Methods that blur its primary responsibility.
  - In a well-designed program each class is responsible only for one thing. When a class deals with multiple product types, it may be worth extracting its factory methods into a stand-alone factory class or a full-blown Abstract Factory implementation.

**Examples**: symfony Messenger AdapterFactoryInterface implemented in AmqpAdapterFactory

### BUILDER
> Builder is a creational design pattern that lets you construct complex objects step by step. The pattern allows you to produce different types and representations of an object using the same construction code.

**Applicability**
- **Use the Builder pattern to get rid of a “telescopic constructor”.**
  - Say you have a constructor with ten optional parameters. Calling such a beast is very inconvenient; therefore, you overload the constructor and create several shorter versions with fewer parameters. These constructors still refer to the main one, passing some default values into any omitted parameters.
    The Builder pattern lets you build objects step by step, using only those steps that you really need. After implementing the pattern, you don’t have to cram dozens of parameters into your constructors anymore.
- **Use the Builder pattern when you want your code to be able to create different representations of some product** (for example, stone and wooden houses).
  - The Builder pattern can be applied when construction of various representations of the product involves similar steps that differ only in the details.
  - The base builder interface defines all possible construction steps, and concrete builders implement these steps to construct particular representations of the product. Meanwhile, the director class guides the order of construction.

- **Use the Builder to construct Composite trees or other complex objects.**
  - The Builder pattern lets you construct products step-by-step. You could defer execution of some steps without breaking the final product. You can even call steps recursively, which comes in handy when you need to build an object tree.
  - **A builder doesn’t expose the unfinished product while running construction steps**. 
  This prevents the client code from fetching an incomplete result.

**Examples**: doctrine QueryBuilder, symfony FormBuilder 

### PROTOTYPE
Prototype is a creational design pattern that lets you copy existing objects without making your code dependent on their classes.

- It's mainly a `clone HeavyInstanciatedClass` thing
- Not used so much in php, Symfony HttpFoundation Request::duplicate()
- Usually defines a method __clone() to treat the clone behaviour (eg: circular reference etc.)

### SINGLETON
Singleton is a creational design pattern that lets you ensure that a class has only one instance, while providing a global access point to this instance.

**Identification:** 
Singleton can be recognized by a static creation method, which returns the same cached object, 
and of course private/protected `__contructor` and inhibited `__clone` or `__wakeup`.

**Examples:** Logger, configuration storage

## B. STRUCTURAL

### ADAPTER
Adapter is a structural design pattern that allows objects with incompatible interfaces to collaborate.

**Applicability**  
- Use the Adapter class when you want to use some existing class, but its interface isn’t compatible with the rest of your code.
- The Adapter pattern lets you create a middle-layer class that serves as a translator between your code and a legacy class, a 3rd-party class or any other class with a weird interface.
- Use the pattern when you want to reuse several existing subclasses that lack some common functionality that can’t be added to the superclass.

### *BRIDGE

### COMPOSITE
Composite is a structural design pattern that lets you compose objects into tree structures and then work with these structures as if they were individual objects.

Examples: Symfony forms, product / combos

### DECORATOR or Wrapper 
https://refactoring.guru/design-patterns/decorator  
https://refactoring.guru/design-patterns/decorator/php/example  
https://betterprogramming.pub/decorator-c04fae63dfff  

> Decorator is a structural design pattern that lets you attach new behaviors to objects by placing these objects inside special wrapper objects that contain the behaviors.

**Applicability**  
- Use the Decorator pattern when you need to be able to assign extra behaviors to objects at runtime without breaking the code that uses these objects.
- The Decorator lets you structure your business logic into layers, create a decorator for each layer and compose objects with various combinations of this logic at runtime. The client code can treat all these objects in the same way, since they all follow a common interface.
- Use the pattern when it’s awkward or not possible to extend an object’s behavior using inheritance. 
- Many programming languages have the `final` keyword that can be used to prevent further extension of a class. 
  For a final class, the only way to reuse the existing behavior would be to wrap the class with your own wrapper, using the Decorator pattern.

### FLYWEIGHT or CACHE 
> Flyweight is a structural design pattern that lets you fit more objects into the available amount of RAM by sharing common parts of state between multiple objects instead of keeping all the data in each object.
- **Flyweight objects are immutable**
- A flyweight should initialize its state just once, via constructor parameters. It shouldn’t expose any setters or public fields to other objects.

> To minimise memory usage, a Flyweight shares as much as possible memory with similar objects. It is needed when a large amount of objects is used that don’t differ much in state. A common practice is to hold state in external data structures and pass them to the flyweight object when needed.

**Applicability**
- Use the Flyweight pattern only when your program must support a huge number of objects which barely fit into available RAM.

### PROXY
> Proxy is a structural design pattern that lets you provide a substitute or placeholder for another object. A proxy controls access to the original object, allowing you to perform something either before or after the request gets through to the original object.

**Applicability**
- Lazy initialization (**virtual proxy**). This is when you have a heavyweight service object that wastes system resources by being always up, even though you only need it from time to time.
- Access control (protection proxy). This is when you want only specific clients to be able to use the service object; for instance, when your objects are crucial parts of an operating system and clients are various launched applications (including malicious ones).
- Local execution of a remote service (remote proxy). This is when the service object is located on a remote server.
- Logging requests (**logging proxy**). This is when you want to keep a history of requests to the service object.
- Caching request results (**caching proxy**). This is when you need to cache results of client requests and manage the life cycle of this cache, especially if results are quite large.


## C. BEHAVIORAL

### CHAIN OF RESPONSIBILITY or CHAIN OF COMMAND
> Chain of Responsibility is a behavioral design pattern that lets you pass requests along a chain of handlers. Upon receiving a request, each handler decides either to process the request or to pass it to the next handler in the chain.

**Applicability**
- Use the Chain of Responsibility pattern when your program is expected to process different kinds of requests in various ways, but the exact types of requests and their sequences are unknown beforehand.
- Use the pattern when it’s essential to execute several handlers in a particular order.
- Use the CoR pattern when the set of handlers and their order are supposed to change at runtime.


### COMMAND
> Command is a behavioral design pattern that turns a request into a stand-alone object that contains all information about the request. This transformation lets you pass requests as a method arguments, delay or queue a request’s execution, and support undoable operations.

### ITERATOR 
**Usage examples:** The pattern is very common in PHP code. Many frameworks and libraries use it to provide a standard way for traversing their collections.

The PHP has a built-in **Iterator** interfaces that can be used for building custom iterators compatible with the rest of the PHP code.

**Identification:** Iterator is easy to recognize by the navigation methods (such as next, previous and others). Client code that uses iterators might not have direct access to the collection being traversed (also implementing Traversable interface).


### MEDIATOR
> Mediator is a behavioral design pattern that lets you reduce chaotic dependencies between objects. The pattern restricts direct communications between the objects and forces them to collaborate only via a mediator object.

This pattern provides an easy way to decouple many components working together. It is a good alternative to Observer IF you have a “central intelligence”, like a controller (but not in the sense of the MVC).
All components (called Colleague) are only coupled to the Mediator interface and it is a good thing because in OOP, one good friend is better than many. This is the key-feature of this pattern.

### MEMENTO

### OBSERVER
**Usage examples:** PHP has several built-in interfaces (SplSubject, SplObserver) that can be used to make your implementations of the Observer pattern compatible with the rest of the PHP code.

**Identification:** The pattern can be recognized by subscription methods, that store objects in a list and by calls to the update method issued to objects in that list.

### STATE
> State is a behavioral design pattern that lets an object alter its behavior when its internal state changes. It appears as if the object changed its class.

> The pattern extracts state-related behaviors into separate state classes and forces the original object to delegate the work to an instance of these classes, instead of acting on its own.

**Applicability**
- Use the State pattern when you have an object that behaves differently depending on its current state, the number of states is enormous, and the state-specific code changes frequently.
- Use the pattern when you have a class polluted with massive conditionals that alter how the class behaves according to the current values of the class’s fields.
- Use State when you have a lot of duplicate code across similar states and transitions of a condition-based state machine.
 
**Usage examples:** The State pattern is occasionally used in PHP for turning large and cumbersome state machines based on switch operators into objects.

**Identification:** State pattern can be recognized by methods that change their behavior depending on the objects’ state, controlled externally.

### STRATEGY
**Usage examples:** The Strategy pattern is often used in PHP code, especially when it’s necessary to switch algorithms at runtime. However, the pattern has a strong competitor represented by anonymous functions, introduced in PHP 5.3 in 2009.

**Identification:** Strategy pattern can be recognized by a method that lets nested object do the actual work, as well as the setter that allows replacing that object with a different one.

### TEMPLATE METHOD
Template Method is a behavioral design pattern that allows you to defines a skeleton of an algorithm in a base class and let subclasses override the steps without changing the overall algorithm’s structure.


### VISITOR
