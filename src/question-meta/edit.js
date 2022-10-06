import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, Button, __experimentalNumberControl as NumberControl, Icon } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useState } from "@wordpress/element";
import './editor.scss';

export default function Edit() {
	const blockProps = useBlockProps();
	const [meta, setMeta] = useEntityProp('postType', 'question', 'meta');
	const [optionInput, setOptionInput] = useState("");
	const [removeItem, setRemoveItem] = useState("");
	//Registered meta from PHP
	const answer = meta.noobs_quiz_question_answer; //store answer
	const mark = meta.noobs_quiz_question_mark; //store mark
	const options = meta.noobs_quiz_question_options || []; //store options
	//Update answer onchange
	const updateAnswer = (newAnswer) => {
		setMeta({ ...meta, noobs_quiz_question_answer: newAnswer });
	};
	//update mark on change
	const updateMark = (newMark) => {
		setMeta({ ...meta, noobs_quiz_question_mark: newMark });
	}
	//Add options to on click
	const handleAddOptions = () => {
		setMeta({ ...meta, noobs_quiz_question_options: options.concat(optionInput) })
		setOptionInput("");
	}
	//Remove option to on click and index come from on focus
	const handleRemoveOptions = () => {
		const newOptions = options.filter((value,index) => {
			return index !== removeItem;
		})

		setMeta({ ...meta, noobs_quiz_question_options: newOptions })
	}
	/* JSX for Question Meta */
	return (
		<div {...blockProps}>
			<TextControl
				label={__("Question Answer")}
				value={answer}
				onChange={updateAnswer}
				placeholder="Please Write Your Question Answer"
			/>

			<NumberControl
				label={__("Question Mark")}
				value={mark}
				onChange={updateMark}
				placeholder="Please Write Your Question Mark"
			/>

			<div className="noobs-quiz__repeater">
				<div className="noobs-quiz__repeater--fields">
					<TextControl
						label={__("Question Options")}
						value={optionInput}
						onChange={(newValue)=> setOptionInput(newValue)}
						placeholder="Please Write Your Question Options"
					/>
					<Button
						variant="primary"
						icon={<Icon icon="plus" />}
						onClick= {handleAddOptions}
					/>
				</div>
				<ul className="noobs-quiz__repeater--list">
					{
						options?.map((value,index)=>(
							<li key={index + 1}>{value}
								<Button
								key={index}
								variant= 'secondary'
								icon={<Icon icon="no-alt"/>}
								onClick={handleRemoveOptions}
								onFocus = {()=>{setRemoveItem(index)}}
								/>
							</li>
						))
					}
				</ul>
			</div>
		</div>
	);
}
