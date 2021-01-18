<?php
namespace Packaged\I18n\Tools\Gettext;

use Packaged\I18n\Tools\TextIDGenerator;

class PoTranslation
{
  const FLAG_FUZZY = 'fuzzy';

  protected $_previous = [];
  protected $_id = '';
  protected $_comments = [];
  protected $_flags = [];
  protected $_references = [];
  protected $_extractedComments = [];
  protected $_context = '';
  protected $_singularSource = '';
  protected $_pluralSource = '';
  protected $_singularTranslation = '';
  protected $_pluralTranslation = '';
  protected $_additionalPluralTranslations = [];

  public function __construct($id)
  {
    $this->_id = $id;
  }

  public function getId()
  {
    return $this->_id;
  }

  public function getContent(): array
  {
    $lines = [];

    foreach($this->_comments as $comment)
    {
      $lines[] = '# ' . $comment;
    }

    foreach($this->_extractedComments as $comment)
    {
      $lines[] = '#. ' . $comment;
    }

    if(!empty($this->_references))
    {
      $lines[] = '#: ' . implode(' ', $this->_references);
    }

    foreach($this->_flags as $flag)
    {
      $lines[] = '#, ' . $flag;
    }
    foreach($this->_previous as $previous => $previousValue)
    {
      $lines[] = '#| ' . $previous . ' ' . $previousValue;
    }

    if($this->_context)
    {
      $lines[] = 'msgctxt "' . $this->_slash($this->_context) . '"';
    }

    $lines[] = 'msgid "' . $this->_slash($this->_singularSource) . '"';
    if(!$this->_pluralSource)
    {
      $lines[] = 'msgstr "' . $this->_slash($this->_singularTranslation) . '"';
    }
    else
    {
      $lines[] = 'msgid_plural "' . $this->_slash($this->_pluralSource) . '"';
      $lines[] = 'msgstr[0] "' . $this->_slash($this->_singularTranslation) . '"';
      $lines[] = 'msgstr[1] "' . $this->_slash($this->_pluralTranslation) . '"';

      $minId = 2;
      foreach($this->_additionalPluralTranslations as $k => $v)
      {
        if($k <= $minId)
        {
          $k = $minId;
        }
        $minId = $k + 1;

        $lines[] = 'msgstr[' . $k . '] "' . $this->_slash($v) . '"';
      }
    }
    return $lines;
  }

  public function __toString()
  {
    return implode(PHP_EOL, $this->getContent());
  }

  protected function _slash($text)
  {
    $text = trim(addcslashes(stripslashes($text), '"'));
    if(strpos($text, "\n") > 0)
    {
      return implode('"' . PHP_EOL . '"', explode("\n", $text));
    }
    return $text;
  }

  public static function fromString(string $translationString): ?PoTranslation
  {
    $translation = new static('');
    $lines = explode("\n", $translationString);
    $currentCode = '';
    foreach($lines as $line)
    {
      $line = trim($line);
      if($line[0] == '#')
      {
        if(!isset($line[1]))
        {
          continue;
        }
        switch($line[1])
        {
          case '.':
            $translation->_extractedComments[] = trim(substr($line, 2));
            break;
          case ':':
            $translation->_references = array_merge($translation->_references, explode(' ', trim(substr($line, 2))));
            break;
          case ',':
            $flag = trim(substr($line, 2));
            $translation->_flags[$flag] = $flag;
            break;
          case '|':
            [$key, $value] = explode(' ', trim(substr($line, 2)), 2);
            $translation->_previous[$key] = $value;
            break;
          case ' ':
          default:
            $translation->_comments[] = trim(substr($line, 1));
            break;
        }
      }
      else
      {
        //Assume continuation
        if($line[0] == '"')
        {
          $content = $line;
        }
        else
        {
          [$currentCode, $content] = explode(" ", $line, 2);
        }
        $append = trim($content);
        $starts = $append[0] === '"';
        $ends = $append[strlen($append) - 1] === '"';
        $append = substr($append, $starts ? 1 : 0, $ends ? -1 : null) . PHP_EOL;
        switch($currentCode)
        {
          case 'msgid':
            $translation->_singularSource .= $append;
            break;
          case 'msgstr':
          case 'msgstr[0]':
            $translation->_singularTranslation .= $append;
            break;
          case 'msgstr[1]':
            $translation->_pluralTranslation .= $append;
            break;
          case 'msgid_plural':
            $translation->_pluralSource .= $append;
            break;
          case 'msgctxt':
            $translation->_context .= $append;
            break;
          default:
            if(substr($currentCode, 0, 7) == 'msgstr[')
            {
              $codeKey = rtrim(substr($currentCode, 7, ']'));
              if(!isset($translation->_additionalPluralTranslations[$codeKey]))
              {
                $translation->_additionalPluralTranslations[$codeKey] = '';
              }
              $translation->_additionalPluralTranslations[$codeKey] .= $append;
            }
            break;
        }
      }
    }
    $translation->_singularSource = rtrim($translation->_singularSource);
    $translation->_singularTranslation = rtrim($translation->_singularTranslation);
    $translation->_pluralTranslation = rtrim($translation->_pluralTranslation);
    $translation->_pluralSource = rtrim($translation->_pluralSource);
    $translation->_context = rtrim($translation->_context);
    foreach($translation->_additionalPluralTranslations as $k => $v)
    {
      $translation->_additionalPluralTranslations[$k] = rtrim($v);
    }

    if(!empty($translation->_references))
    {
      $translation->_id = $translation->_references[0];
    }

    if(empty($translation->_id) && $translation->_singularSource)
    {
      if(strpos($translation->_singularSource, " ") > -1)
      {
        $translation->_id = TextIDGenerator::generate($translation->_singularSource);
      }
      else
      {
        $translation->_id = $translation->_singularSource;
      }
    }

    return $translation->_id !== '' ? $translation : null;
  }

  /**
   * @return array
   */
  public function getComments(): array
  {
    return $this->_comments;
  }

  /**
   * @param array $comments
   *
   * @return PoTranslation
   */
  public function setComments(array $comments): PoTranslation
  {
    $this->_comments = $comments;
    return $this;
  }

  /**
   * @return array
   */
  public function getReferences(): array
  {
    return $this->_references;
  }

  /**
   * @param array $developerComments
   *
   * @return PoTranslation
   */
  public function setReferences(array $developerComments): PoTranslation
  {
    $this->_references = $developerComments;
    return $this;
  }

  /**
   * @return array
   */
  public function getExtractedComments(): array
  {
    return $this->_extractedComments;
  }

  /**
   * @param array $translatorComments
   *
   * @return PoTranslation
   */
  public function setExtractedComments(array $translatorComments): PoTranslation
  {
    $this->_extractedComments = $translatorComments;
    return $this;
  }

  /**
   * @return bool
   */
  public function needsWork(): bool
  {
    return isset($this->_flags[self::FLAG_FUZZY]);
  }

  /**
   * @param bool $needsWork
   *
   * @return PoTranslation
   */
  public function setNeedsWork(bool $needsWork): PoTranslation
  {
    if($needsWork)
    {
      return $this->setFlag(self::FLAG_FUZZY);
    }
    return $this->removeFlag(self::FLAG_FUZZY);
  }

  public function setFlag(string $flag): PoTranslation
  {
    $this->_flags[$flag] = $flag;
    return $this;
  }

  public function removeFlag(string $flag): PoTranslation
  {
    unset($this->_flags[$flag]);
    return $this;
  }

  public function hasFlag(string $flag): bool
  {
    return isset($this->_flags[$flag]);
  }

  public function getFlags(): array
  {
    return $this->_flags;
  }

  /**
   * @return string
   */
  public function getContextString(): string
  {
    return $this->_context;
  }

  /**
   * @param string $context
   *
   * @return PoTranslation
   */
  public function setContextString(string $context): PoTranslation
  {
    $this->_context = $context;
    return $this;
  }

  /**
   * @return string
   */
  public function getSingularSource(): string
  {
    return $this->_singularSource;
  }

  /**
   * @param string $singularSource
   *
   * @return PoTranslation
   */
  public function setSingularSource(string $singularSource): PoTranslation
  {
    $this->_singularSource = $singularSource;
    return $this;
  }

  /**
   * @return string
   */
  public function getPluralSource(): string
  {
    return $this->_pluralSource;
  }

  /**
   * @param string $pluralSource
   *
   * @return PoTranslation
   */
  public function setPluralSource(string $pluralSource): PoTranslation
  {
    $this->_pluralSource = $pluralSource;
    return $this;
  }

  /**
   * @return string
   */
  public function getSingularTranslation(): string
  {
    return $this->_singularTranslation;
  }

  /**
   * @param string $singularTranslation
   *
   * @return PoTranslation
   */
  public function setSingularTranslation(string $singularTranslation): PoTranslation
  {
    $this->_singularTranslation = $singularTranslation;
    return $this;
  }

  /**
   * @return string
   */
  public function getPluralTranslation(): string
  {
    return $this->_pluralTranslation;
  }

  /**
   * @param string $pluralTranslation
   *
   * @return PoTranslation
   */
  public function setPluralTranslation(string $pluralTranslation): PoTranslation
  {
    $this->_pluralTranslation = $pluralTranslation;
    return $this;
  }

  /**
   * @return array
   */
  public function getAdditionalPluralTranslations(): array
  {
    return $this->_additionalPluralTranslations;
  }

  /**
   * @param array $additionalPluralTranslations
   *
   * @return PoTranslation
   */
  public function setAdditionalPluralTranslations(array $additionalPluralTranslations): PoTranslation
  {
    $this->_additionalPluralTranslations = $additionalPluralTranslations;
    return $this;
  }

}
