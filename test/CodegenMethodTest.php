<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

use function Facebook\HackCodegen\LegacyHelpers\{
  codegen_class,
  codegen_constructor,
  codegen_generated_from_method_with_key,
  codegen_method
};

final class CodegenMethodTest extends CodegenBaseTest {

  public function testSimpleGetter(): void {
    $code = codegen_method('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      ->setDocBlock('Return the name of the user.')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testAbstractProtectedAndParams(): void {
    $code = codegen_method('getSchema')
      ->addParameter('string $name')
      ->setIsAbstract()
      ->setProtected()
      ->render();

    $this->assertUnchanged($code);
 }

  public function testAsync(): void {
    $code = codegen_method('genFoo')
      ->setIsAsync()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testPrivateAndStaticWithEmptyBody(): void {
    $code = codegen_method('doNothing')
      ->setIsStatic()
      ->setPrivate()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testManualSection(): void {
    $method = codegen_method('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody();

    codegen_class('MyClass')->addMethod($method);
    $code = $method->render();

    $this->assertUnchanged($code);
  }

  public function testConstructor(): void {
    $code = codegen_constructor()
      ->addParameter('string $name')
      ->setBody('$this->name = $name;')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDocBlockCommentsWrap(): void {
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = codegen_method('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      // 81 characters
      ->setDocBlock(str_repeat('x', 78))
      ->setGeneratedFrom(
        codegen_generated_from_method_with_key(
          'EntTestSchema',
          'getFields',
          'name'
        )
      )->render();

    $this->assertUnchanged($code);
  }
}