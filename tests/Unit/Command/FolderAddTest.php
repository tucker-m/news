<?php
/**
 * @author Sean Molenaar <sean@seanmolenaar.eu>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\News\Tests\Unit\Command;

use OCA\News\Command\Config\FolderAdd;
use OCA\News\Db\Folder;
use OCA\News\Service\FolderServiceV2;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FolderAddTest extends TestCase
{
    /** @var MockObject|FolderServiceV2 */
    protected $service;
    /** @var MockObject|InputInterface */
    protected $consoleInput;
    /** @var MockObject|OutputInterface */
    protected $consoleOutput;

    /** @var FolderAdd */
    protected $command;

    protected function setUp(): void
    {
        $this->service = $this->getMockBuilder(FolderServiceV2::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->consoleInput = $this->getMockBuilder(InputInterface::class)->getMock();
        $this->consoleOutput = $this->getMockBuilder(OutputInterface::class)->getMock();

        $this->command = new FolderAdd($this->service);
    }

    /**
     * Test a valid call will work
     */
    public function testValid()
    {
        $this->consoleInput->expects($this->exactly(2))
                           ->method('getArgument')
                           ->will($this->returnValueMap([
                               ['name', 'folder'],
                               ['user-id', 'admin'],
                           ]));

        $this->consoleInput->expects($this->exactly(1))
                           ->method('getOption')
                           ->will($this->returnValueMap([
                               ['parent', '0'],
                           ]));

        $feed = $this->createMock(Folder::class);

        $this->service->expects($this->exactly(1))
                           ->method('create')
                           ->with('admin', 'folder', 0)
                           ->willReturn($feed);

        $result = $this->command->run($this->consoleInput, $this->consoleOutput);
        $this->assertSame(0, $result);
    }
}
