# ✅ Final Exam Answer Key (Instructor Resource)

This guide is intended for instructors to evaluate student performance in the **CMSForNerd v3.3** Laboratory.

## Grading Checklist

1. **PHP 8.4 Features:** Did the student use Property Hooks correctly? (No `return` keyword in arrow-style hooks).
2. **Security:** Is `SecurityUtils` used for all file path operations?
3. **Tests:** Does the student's test suite utilize the AAA pattern?

## Logic Solutions

### Question 1: Path Traversal

The student must not use `include $_GET['page']`. They must use a sanitization method or a hard-coded allow-list.

### Question 2: Property Hooks

Check for:
`get => $this->property` (Correct)
`get { return $this->property; }` (Correct)
`get => return $this->property` (ERROR: Fatal Syntax Error)
