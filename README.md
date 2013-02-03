SimplifiedPHP
=============

Simplified PHP is a new approach to creating a programming language that works with the widespread distribution, ease of installation, and reliability of PHP, while avoiding the overly complicated syntax and nuances. A minimalistic approach has been taken, and features will only be added if they are deemed essential for the purpose of this project.

There are two major structures in SPHP: the Entity and the Expression, each with their own limitations and feature sets.

# Entities

In SPHP, everything is an Entity except the single value Void, which represents nothingness, or a traditional boolean false.

An Entity is delimited with square brackets, and optional commas for multiple items per line. Entity variables must be assigned as key: value pairs.

### Example:

  a: [name: "Dan", age: 20]

  b: [name: "Dan"
       age: 20]

  a == b

# Expressions

Expressions contain a set of functionality to be executed at a later time. Expressions must always be provided with default arguments, if any arguments are required. Void may be used as a default argument.

### Example:

  add: {a + b}[a: 0, b: 0]

  4 == add[b: 4]

  6 == add[b: 3, a: 3]

You may curry arguments and provide the remaining arguments at any time. Attempting to provide the same arguments twice will result in an error.

### Example:

  add: {a + b}[a: 0, b: 0]

  add2: add & [a: 2]

  7 == add2[b: 5]

It's perfectly fine to curry all of the arguments. When you want to invoke the function, just pass it an empty Entity.

### Example:

 add: {a + b}[a: 0, b:0]

 add6and4: add & [a: 6, b: 4]

 10 == add6and4[]

# Expressions as Entity Attributes

Expressions may be defined inline within an entity, and vice-versa. It's perfectly fine to attach expressions to entities after they are created as well.

### Example:

