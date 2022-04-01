
# interface vs abstract

Interface Class                                         | Abstract Class 

Interface class supports multiple inheritance feature   | Abstract class does not support multiple inheritances.

This does not contain properties but constants.         | Abstract class does contain a data member.

The interface does not allow constructors.              | The abstract class supports constructors.

An interface class only contains incomplete members     | Abstract class contains both incomplete(i.e. abstract) and complete members.
which refer to the signature of the member.             |

Since everything is assumed to be public, an interface  | An abstract class can contain access modifiers within functions, and properties.
class does not have access modifiers by default.

Any member of an interface cannot be static.            | Only a complete member of the abstract class can be static.

An abstract class can implement part of an interface letting the child class to implement the rest


# hashing vs. encryption
Encryption is two-way, the data can be decrypted, so it is readable again. 
Hashing, on the other hand, is one-way, meaning the plaintext is scrambled into a unique digest, through the use of a salt, that cannot be decrypted.


**Asymmetric** encryption uses two different keys, a public and private key, for encryption and decryption. The private key is used to encrypt data, and is kept a secret from everyone but the person encrypting the data. 
The public key is available for anyone, and is used for decryption. 
Using asymmetric encryption, the authenticity of the data can be verified, because if the data was modified in transit, it would not be able to be re-encrypted with the private key. 

**Symmetric** encryption uses the same key for both encryption and decryption. This type of encryption uses less processing power and is faster, but is less secure as only one key is used.

symmetric: AES256
asymmetric: RSA

hashing:
    Message Digest Algorithm (MD5)
    Secure Hashing Algorithm (SHA-1, SHA-2, SHA-3)
    Cyclical Reduction Check (CRC32)
    bcrypt
    sodium
    auto


# PHP Standards
https://www.php-fig.org/psr/psr-4/  
https://www.php-fig.org/psr/psr-12/  


# what is dependency injection
# xdebug 

# What's new in php 8
- union types
- nullsafe operator
- named arguments
- attributes (annotations)
- match expression (no break, strict comparisons)
- constructor property promotion
- static return type
- mixed type (property type or return type)
- throw expression
- inheritance with private method
- ::class on objects
- non-capturing catches (you can omit the variable)
- trailing comma in parameter lists
- str_contains, str_starts_with, str_ends_with
- abstract methods in traits (proper method signature validation)
- Type annotations for internal functions
- ext-json always available
- Consistent type errors: TypeError
- Reclassified engine warnings https://stitcher.io/blog/new-in-php-8#reclassified-engine-warnings-rfc
- @ operator no longer silences fatal errors
- Default error reporting level E_ALL
- Fatal error for incompatible method signatures
  See all here: https://stitcher.io/blog/new-in-php-8

# What's new in php 8.1
- Enums
- Fibers 
- Array unpacking with string keys
- new in initializers
- Readonly properties
- intersection types (like union types but with &)
- The `never` return type (never differs from void in that void still allows the program to continue)
- New `array_is_list` function
- final class constants
- New fsync function

