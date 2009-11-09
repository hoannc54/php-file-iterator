<?php
/**
 * php-file-iterator
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   File
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.1.0
 */

require_once 'File/Iterator.php';

/**
 * Factory Method implementation that creates a File_Iterator that operates on
 * an AppendIterator that contains an RecursiveDirectoryIterator for each given
 * path.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/php-file-iterator/tree
 * @since     Class available since Release 1.1.0
 */
class File_Iterator_Factory
{
    /**
     * @param  array|string $paths
     * @param  array|string $suffixes
     * @param  array|string $prefixes
     * @param  array        $exclude
     * @return File_Iterator
     */
    public static function getFileIterator($paths, $suffixes = '', $prefixes = '', array $exclude = array())
    {
        if (is_string($paths)) {
            $paths = array($paths);
        }

        if (is_string($prefixes)) {
            $prefixes = array($prefixes);
        }

        if (is_string($suffixes)) {
            $suffixes = array($suffixes);
        }

        $pathIterator = new AppendIterator;

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $pathIterator->append(
                  new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path)
                  )
                );
            }
        }

        return new File_Iterator($pathIterator, $suffixes, $prefixes, $exclude);
    }

    /**
     * @param  array|string $paths
     * @param  array|string $suffixes
     * @param  array|string $prefixes
     * @param  array        $exclude
     * @return array
     */
    public static function getFilesAsArray($paths, $suffixes = '', $prefixes = '', array $exclude = array())
    {
        $result = array();

        $iterator = self::getFileIterator(
          $paths, $suffixes, $prefixes, $exclude
        );

        foreach ($iterator as $file) {
            $result[] = $file->getRealPath();
        }

        foreach ($paths as $path) {
            if (is_file($path)) {
                $result[] = realpath($path);
            }
        }

        return $result;
    }
}
?>